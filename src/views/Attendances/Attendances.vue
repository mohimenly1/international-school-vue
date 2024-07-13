<template>
    <div id="body-container">

        <img src="~@/assets/img/logos/pexels-pixabay-415992.jpg" class="background-img" alt="Background Image" />
      <nav class='tbar'>
        <span class='percent'>70%</span>
        <i class="fas fa-battery-three-quarters"></i>
        <i class="fas fa-wifi"></i>
        <span class="time">7:21 PM</span>
      </nav>
    
      <div class='content'>
        <div class='input-wrap'>
            <img src="~@/assets/img/logos/logo-school-one.png" class="pic" alt="Profile Picture" />
          <div class='wrap'>
            <h3 class='name'>Caledonian School</h3>
          </div>
          <input style="margin-left: 31px;" type='password' id='password' placeholder='Enter Password' autocomplete='off' v-model="userPassword" @focusin="showArrow">
          <button class='arrow' v-show="arrowVisible" @click="checkPassword"><i class="fas fa-arrow-right"></i></button>
        </div>
        <div class='cancel-wrap'>
          <button class='x'>X</button>
          <span>Cancel</span>
        </div>
      </div>
      
      <div class='chrome' v-show="chromeVisible">
        <nav class='toolbar'>
          <div>
            <i class="fas fa-circle" id='red'></i>
            <i class="fas fa-circle" id='yellow'></i>
            <i class="fas fa-circle" id='green'></i>
          </div>
          <div class='tab'><i class="fas fa-lock"></i> Security <span class='close'>x</span></div>
        </nav>
        
        <div class='bar'>
          <div class='pointers'>
            <i class="fas fa-arrow-left"></i>
            <i class="fas fa-arrow-right"></i>
            <i class="fas fa-redo-alt"></i>
          </div>
          <span class='search'>examplepasswordsecurity.com</span>
        </div>
        <main>
          <h2 class='articleh2'>Secure Passwords</h2>
          <div class='words'>
          <p>Secure Passwords are key to ensure your computer, accounts, and identity are secure. Here are some rules to ensure your password is secure!</p>
          <ol class='rules'>
            <li>Use a different password for each account</li> 
            <li>Use a password manager to keep track of your passwords</li>  
            <li>Dont leave passwords on stickie notes etc.</li>  
            <li>Dont use "password", "1234", or any placeholder or obvious passwords</li>  
            <li>Use a variety of characters in your password</li>  
           </ol>
          </div>
        </main>
      </div>
    </div>
  </template>
  
  <script>
  export default {

    data() {
      return {
        password: 'ENTER PASSWORD',
        userPassword: '',
        arrowVisible: false,
        chromeVisible: false
      };
    },
    methods: {
      showArrow() {
        this.arrowVisible = true;
      },
      checkPassword() {
        if (this.userPassword.toUpperCase() === this.password) {
          this.$refs.content.style.display = 'none';
          this.$refs.tbar.style.color = 'black';
          this.$refs.tbar.style.backgroundColor = 'rgb(200,200,200)';
          this.chromeVisible = true;
        } else {
          this.shakePasswordInput();
        }
      },
      shakePasswordInput() {
        const passwordInput = this.$refs.password;
        passwordInput.classList.add('shake');
        setTimeout(() => {
          passwordInput.classList.remove('shake');
        }, 500);
      }
    }
  };
  </script>
  
  <style scoped>
  .background-img {
    position: absolute;
    top: 0;
    left: 0;
    height: 100vh;
    width: 100%;
    z-index: -1; /* Place behind other content */
    pointer-events: none; /* Allow clicks to pass through */
  }
  #body-container {
    margin: 0;
    padding: 0;
    font-family: helvetica;
    height: 100vh;
    width: 100%;
    background-size: cover;
    background-repeat: no-repeat;
    color: white;
  }
  
  .tbar {
    color: white;
    font-size: 12px;
    display: flex;
    padding: 5px;
    justify-content: flex-end;
    place-items: center;
  }
  
  .tbar > i, .tbar > .time{
    padding-right: 8px;
  }
  
  .percent {
    padding-right: 5px;
  }
  
  .content {
    width: 100%;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }
  
  
  .pic {
    margin: 0 auto;
    margin-left: 91px;
    width: 80px;
    height: 80px;
    border-radius: 100%;
    object-fit: cover; /* Ensures the image covers the container */
    border: 2px solid white; /* Example border */
  }
  
  /* had to do this to center name "Andrew" */
  .wrap {
    display: flex;
    font-weight: lighter;
    font-size: 18px;
  }
  
  .name {
    margin: 20px auto;
    font-family: 'Heebo', sans-serif;
    letter-spacing: 1px;
    color: #ffffff;
  }
  
  #password {
    font-size: 17px;
    color: black;
    background-color: hsla(0, 0%, 100%, 0.9);
    border: none;
    border-radius: 5px;
    height: 30px;
    padding: 0 5px;
  
  }
  
  #password::placeholder {
    font-size: 14px;
    vertical-align: center;
    color: rgb(170,170,170);
  }
   
  input:focus {
    outline: none;
  }
  
  .arrow {
    width: 25px;
    height: 25px;
    font-size: 14px;
    border-radius: 100%;
    display: block;
    justify-content: center;
    align-items: center;
    background-color: rgb(170,170,170);
    border: none;
    color: rgba(0,0,0,.7);
    padding: 3px;
    margin-left: 5px;
    margin-right: -35px;
  }
  
  button:focus {
    outline: none;
  }
  
  button:active {
    border: none;
    background-color: hsla(0, 0%, 75%, 90%);
  }
  
  .x {
    width: 25px;
    height: 25px;
    background-color: hsla(0, 0%, 45%, 90%);
    color: white;
    font-size: 15px;
    border-radius: 100%;
    margin: 10px auto;
  }
  
  .cancel-wrap {
    display: flex;
    flex-direction: column;
    margin-top: 140px;
  }
  
  .chrome {
    width: 90%;
    margin: 10px auto 0 auto;
    height: 100vh;
    background-color: white;
    display: none;
    border-radius: 5px;
  }
  
  .toolbar {
    background-color: hsla(0, 0%, 85%, 90%);
    display: flex;
    flex-direction: row;
    padding: 10px 10px 0 10px;
    border-radius: 5px 5px 0 0;
  }
  
  .toolbar > div > i {
    font-size: 12px;
    padding: 1px;
  }
  
  .tab {
    background-color: white;
    color: black;
    font-size: 10px;
    width: 120px;
    height: 25px; 
    border-radius: 10px 10px 0 0;
    display: flex;
    align-items: center;
    margin-left: 10px;
  }
  
  .close {
    margin-left: 45px;
  }
  
  .fa-lock {
    margin: 0 5px;
  }
  
  #red {
    color: #DE4646;
  }
  
  #yellow {
    color: #E8C907;
  }
  
  #green {
    color: #32E406;
  }
  
  .bar {
    background-color: white;
    width: 100%;
    padding: 15px 0;
    border-bottom: 1px solid hsla(0, 0%, 85%, 90%);
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
  }
  
  .pointers > i {
    color: grey;
    padding: 0 5px;
    font-size: 12px;
  }
  
  .search {
    border-radius: 30px;
    font-size: 14px;
    background-color: hsla(0, 0%, 85%, 90%);
    color: black;
    padding-left: 20px;
    padding: 5px 0 5px 20px;
    height: 15px;
    width: 75%;
    margin-right: 50px;
  }
  
  .fa-redo-alt {
    margin-right: 10px;
  }
  
  .fa-arrow-left {
    margin-left: 10px;
  }
  
  main {
    width: 80%;
    margin: 0 auto;
    text-align: center;
    margin-top: 20px;
    color: black;
  }
  
  .articleh2 {
    color: red;
    background-color: #E1E1E1;
    padding: 20px;
  }
  
  .words {
    text-align: left;
    font-size: 16px;
  }
  </style>
  