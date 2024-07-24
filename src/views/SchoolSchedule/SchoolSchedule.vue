<template>
    <DayPilotCalendar id="dp" :config="config" ref="monthRef" />
  </template>
  
  <script setup>
  import { DayPilotCalendar, DayPilot } from '@daypilot/daypilot-lite-vue';
  import { ref, reactive, onMounted, computed, watch } from 'vue';
  import { useStore } from 'vuex';
  
  const store = useStore();
  
  const monthRef = ref(null);
  const colors = {
    "event": "#3c78d8",
    "task": "#6aa84f",
    "reminder": "#f1c232",
    "holiday": "#cc0000",
  };
  
  const config = reactive({
    startDate: "2024-07-20",
    eventHeight: 30,
    events: [], // Initialize with empty array
    onEventMoved: (args) => {
      updateEvent(args.e);
    },
    onEventResized: (args) => {
      updateEvent(args.e);
    },
    onEventClicked: async (args) => {
      const form = [
        { name: "Text", id: "text" }
      ];
      const modal = await DayPilot.Modal.form(form, args.e.data);
      if (modal.canceled) {
        return;
      }
      updateEvent(modal.result);
    },
    onTimeRangeSelected: async (args) => {
      const modal = await DayPilot.Modal.prompt("Create a new event:", "Event 1");
      const dp = args.control;
      dp.clearSelection();
      if (modal.canceled) { return; }
      addEvent({
        start: args.start,
        end: args.end,
        id: DayPilot.guid(),
        text: modal.result
      });
    },
    onBeforeEventRender: args => {
      const color = colors[args.data.type] || "#3c78d8";
      args.data.backColor = color + "55";
      args.data.borderColor = color + "44";
      args.data.barColor = color + "66";
  
      args.data.areas = [
        {
          right: 4,
          top: 3,
          width: 24,
          height: 24,
          padding: 2,
          fontColor: "#444444",
          backColor: color + "55",
          symbol: "icons/daypilot.svg#threedots-v",
          action: "ContextMenu",
          style: "border-radius: 50%"
        }
      ];
  
      if (args.data.locked) {
        args.data.text += " (locked)";
        args.data.areas.push({
          right: 30,
          top: 3,
          width: 24,
          height: 24,
          padding: 4,
          fontColor: "#444444",
          backColor: color + "55",
          symbol: "icons/daypilot.svg#padlock",
          style: "border-radius: 50%",
          onClick: async args => {
            const modal = await DayPilot.Modal.confirm("Do you really want to unlock this event?");
            if (modal.canceled) {
              return;
            }
            toggleEventLock(args.source);
          }
        });
  
        args.data.moveDisabled = true;
        args.data.resizeDisabled = true;
        args.data.clickDisabled = true;
        args.data.deleteDisabled = true;
      }
    },
    contextMenu: new DayPilot.Menu({
      items: [
        { text: "Edit...", onClick: args => editEvent(args.source) },
        { text: "Delete", symbol: "icons/daypilot.svg#x-4", onClick: args => deleteEvent(args.source) },
        { text: "Lock", symbol: "icons/daypilot.svg#padlock", onClick: args => toggleEventLock(args.source) },
        { text: "-" },
        { text: "Duplicate", onClick: args => duplicateEvent(args.source) },
        { text: "Postpone", symbol: "icons/daypilot.svg#minichevron-right-4", onClick: args => postponeEvent(args.source) },
        { text: "-" },
        {
          text: "Type", items: [
            { text: "Event", icon: "icon icon-blue", onClick: args => updateEventType(args.source, "event") },
            { text: "Task", icon: "icon icon-green", onClick: args => updateEventType(args.source, "task") },
            { text: "Reminder", icon: "icon icon-yellow", onClick: args => updateEventType(args.source, "reminder") },
            { text: "Holiday", icon: "icon icon-red", onClick: args => updateEventType(args.source, "holiday") },
          ]
        }
      ],
      onShow: args => {
        const e = args.source;
        const locked = e.data.locked;
  
        // update the lock/unlock text
        args.menu.items[2].text = locked ? "Unlock" : "Lock";
  
        // disable actions for locked
        args.menu.items[0].disabled = locked;
        args.menu.items[1].disabled = locked;
        args.menu.items[5].disabled = locked;
        args.menu.items[7].disabled = locked;
      }
    })
  });
  
  const addEvent = (event) => {
    monthRef.value.control.events.add(event);
    store.dispatch('addEvent', event);
  };
  
  const updateEvent = (event) => {
    monthRef.value.control.events.update(event);
    store.dispatch('updateEvent', event);
  };
  
  const deleteEvent = async (e) => {
    const modal = await DayPilot.Modal.confirm("Do you really want to delete this event?");
    if (modal.canceled) {
      return;
    }
    monthRef.value.control.events.remove(e);
    store.dispatch('deleteEvent', e.id());
  };
  
  const duplicateEvent = (e) => {
    const newEvent = {
      ...e.data,
      id: DayPilot.guid()
    };
    addEvent(newEvent);
  };
  
  const postponeEvent = (e) => {
    e.data.start = e.start().addDays(1);
    e.data.end = e.end().addDays(1);
    updateEvent(e);
  };
  
  const updateEventType = (e, type) => {
    e.data.type = type;
    updateEvent(e);
  }
  
  const toggleEventLock = (e) => {
    e.data.locked = !e.data.locked;
    updateEvent(e);
  };
  
  const loadEvents = () => {
    const events = store.getters.events;
    config.events = events;
  };
  
  onMounted(() => {
    loadEvents();
    store.dispatch('setEvents', store.getters.events);
  });
  
  watch(() => store.getters.events, () => {
    loadEvents();
  });
  </script>
  
  
  <style>
  
  .month_default_event_inner {
    border-radius: 20px;
    padding-left: 35px;
  
    white-space: nowrap;
  }
  
  .month_default_event_bar_inner {
    width: 30px;
    border-radius: 20px;
  }
  
  .month_default_event svg:hover {
    color: #000000;
    cursor: pointer;
  }
  
  /* context menu icons */
  .icon:before {
    position: absolute;
    left: 0px;
    margin-left: 8px;
    margin-top: 3px;
    width: 14px;
    height: 14px;
    content: '';
  }
  
  .icon-blue:before { background-color: #3c78d8; }
  .icon-green:before { background-color: #6aa84f; }
  .icon-yellow:before { background-color: #f1c232; }
  .icon-red:before { background-color: #cc0000; }
  
  </style>
  