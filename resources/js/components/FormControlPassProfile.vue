<template>
    <div class="password-input">
      <div class="icon left">
        <i class="fas fa-lock"></i>
      </div>
      <input class="h-14"
      :type="inputType" 
      :placeholder="placeholder" 
     
      v-model="computedValue"
        :name="name"
        :maxlength="maxlength"
        :inputmode="inputmode"
        :autocomplete="autocomplete"
        :required="required"
        
        >
      <div class="icon right" @click="toggleShowPassword">
        <i :class="showPassword ? 'fas fa-eye' : 'fas fa-eye-slash'"></i>
      </div>
    </div>
  </template>
  
  <script>
  import Form from "@/form.js";
  export default {
    data() {
      return {
        form: new Form({
        
          password: "",
        }),
      
        showPassword: false
      }
    },
    props: {
      placeholder: {
        type: String,
        default: 'Enter your password'
      },
      name: {
      type: String,
      default: null,
    },
    id: {
      type: String,
      default: null,
    },
    autocomplete: {
      type: String,
      default: null,
    },
    maxlength: {
      type: String,
      default: null,
    },
    placeholder: {
      type: String,
      default: null,
    },
    inputmode: {
      type: String,
      default: null,
    },
    icon: {
      type: String,
      default: null,
    },
    options: {
      type: Array,
      default: null,
    },
    type: {
      type: String,
      default: "text",
    },
    modelValue: {
      type: [String, Number, Boolean, Array, Object],
      default: "",
    },
  
    },
    computed: {
      inputType() {
        return this.showPassword ? 'text' : 'password';
      },
      computedValue: {
        get() {
          return this.modelValue;
        },
        set(value) {
          this.$emit('update:modelValue', value);
        }
      }
      },
    methods: {
      toggleShowPassword() {
        this.showPassword = !this.showPassword;
  
        // When the eye icon is clicked, replace the password input field with a new field
        // that has its type attribute set to 'text' to reveal the password.
        // if (this.showPassword) {
        //   const $passwordInput = this.$el.querySelector('input[type="password"]');
        //   const $newInput = document.createElement('input');
        //   $newInput.type = 'text';
        //   $newInput.value = this.password;
        //   $newInput.placeholder = this.placeholder;
        //   $newInput.classList = $passwordInput.classList;
        //   $passwordInput.parentNode.replaceChild($newInput, $passwordInput);
        // }
        // // When the eye icon is clicked again, replace the new input field with a new
        // // password input field to hide the password again.
        // else {
        //   const $textInput = this.$el.querySelector('input[type="text"]');
        //   const $newInput = document.createElement('input');
        //   $newInput.type = 'password';
        //   $newInput.value = this.password;
        //   $newInput.placeholder = this.placeholder;
        //   $newInput.classList = $textInput.classList;
        //   $textInput.parentNode.replaceChild($newInput, $textInput);
        // }
      },
      updateValue(value) {
        this.computedValue = value;
      }
    }
  };
  </script>
  
  <style scoped>
  .password-input {
    position: relative;
    background-color: transparent;
    border: 1px solid #363636;
    color: #ffffff;
    
  }
  
  .password-input .icon {
    position: absolute;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
  }
  
  .password-input .icon.left {
    left: 13px;
      top: -1%;
      color: rgb(83, 83, 83);
      font-size: 13px;
      
  
  }
  
  .password-input .icon.right {
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: rgb(83, 83, 83);
      font-size: 17px;
  }
  
  
  .password-input input::placeholder {
    color: #ffffff;
  }
  
  
  .password-input input {
    padding-left: 40px;
    padding-right: 30px;
    width: 100%;
    background-color: transparent;
    border: none  ;
   
  }
  
  .password-input input:focus {
    outline: none;
    box-shadow: none;
    border : none;
  }
  
  </style>