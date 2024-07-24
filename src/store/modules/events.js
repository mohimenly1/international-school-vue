// store/modules/events.js
const state = {
    events: JSON.parse(localStorage.getItem('events')) || []
  };
  
  const mutations = {
    SET_EVENTS(state, events) {
      state.events = events;
      localStorage.setItem('events', JSON.stringify(events));
    },
    ADD_EVENT(state, event) {
      state.events.push(event);
      localStorage.setItem('events', JSON.stringify(state.events));
    },
    UPDATE_EVENT(state, updatedEvent) {
      const index = state.events.findIndex(event => event.id === updatedEvent.id);
      if (index !== -1) {
        state.events.splice(index, 1, updatedEvent);
        localStorage.setItem('events', JSON.stringify(state.events));
      }
    },
    DELETE_EVENT(state, eventId) {
      state.events = state.events.filter(event => event.id !== eventId);
      localStorage.setItem('events', JSON.stringify(state.events));
    }
  };
  
  const actions = {
    setEvents({ commit }, events) {
      commit('SET_EVENTS', events);
    },
    addEvent({ commit }, event) {
      commit('ADD_EVENT', event);
    },
    updateEvent({ commit }, event) {
      commit('UPDATE_EVENT', event);
    },
    deleteEvent({ commit }, eventId) {
      commit('DELETE_EVENT', eventId);
    }
  };
  
  const getters = {
    events: state => state.events
  };
  
  export default {
    state,
    mutations,
    actions,
    getters
  };
  