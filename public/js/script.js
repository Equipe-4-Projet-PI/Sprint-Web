var pwd = document.getElementById('password');

var Cpwd = document.getElementById('Cpwd');
var eye = document.getElementById('eye');
var eye1 = document.getElementById('eye1');


eye.addEventListener('click',togglePass);
eye1.addEventListener('click',togglePass1);


function togglePass(){

   eye.classList.toggle('active');
   (pwd.type == 'password') ? pwd.type = 'text' : pwd.type = 'password';
   

}


function togglePass1(){
  eye1.classList.toggle('active');
  (Cpwd.type == 'password') ? Cpwd.type = 'text' : Cpwd.type = 'password';
}


// Form Validation

function checkStuff() {
  var username = document.form1.username;
  var password = document.form1.password;
  var msg = document.getElementById('msg');
  
  if (username.value == "") {
    msg.style.display = 'block';
    msg.innerHTML = "Please enter your username";
    email.focus();
    return false;
  }
  
  if (password.value == "") {
    msg.innerHTML = "Please enter your email";
    password.focus();
    return false;
  }
  
 
    return  true
}


function checkStuffS() {
  var username = document.form1.username;
  var email = document.form1.email;
  var password = document.form1.pwd;
  var confirmpwd = document.form1.Cpwd;
  var role = document.form1.role;
  var msg = document.getElementById('msg');
  
  if (username.value == "") {
    msg.style.display = 'block';
    msg.innerHTML = "Please enter your username";
    email.focus();
    return false;
  }
  
  if (password.value == "") {
    msg.innerHTML = "Please enter your email";
    password.focus();
    return false;
  }
  
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  if (!re.test(email.value)) {
    msg.innerHTML = "Please enter a valid email";
    email.focus();
    return false;
  }

  if (password.password == "") {
    msg.innerHTML = "Please enter your password";
    password.focus();
    return false;
  }
  if (confirmpwd.value == "") {
    msg.innerHTML = "Please enter your Confirm Password";
    password.focus();
    return false;
  }
  if (password.value != confirmpwd.value) {
    msg.innerHTML = "Please confirm your password";
    password.focus();
    return false;
  }
  if (role.value == "") {
    msg.innerHTML = "Please choose your role";
    password.focus();
    return false;
  }
    return  true
}




// ParticlesJS

// ParticlesJS Config.
particlesJS("particles-js", {
  "particles": {
    "number": {
      "value": 60,
      "density": {
        "enable": true,
        "value_area": 800
      }
    },
    "color": {
      "value": "#ffffff"
    },
    "shape": {
      "type": "circle",
      "stroke": {
        "width": 0,
        "color": "#000000"
      },
      "polygon": {
        "nb_sides": 5
      },
      "image": {
        "src": "img/github.svg",
        "width": 100,
        "height": 100
      }
    },
    "opacity": {
      "value": 0.1,
      "random": false,
      "anim": {
        "enable": false,
        "speed": 1,
        "opacity_min": 0.1,
        "sync": false
      }
    },
    "size": {
      "value": 6,
      "random": false,
      "anim": {
        "enable": false,
        "speed": 40,
        "size_min": 0.1,
        "sync": false
      }
    },
    "line_linked": {
      "enable": true,
      "distance": 150,
      "color": "#ffffff",
      "opacity": 0.1,
      "width": 2
    },
    "move": {
      "enable": true,
      "speed": 1.5,
      "direction": "top",
      "random": false,
      "straight": false,
      "out_mode": "out",
      "bounce": false,
      "attract": {
        "enable": false,
        "rotateX": 600,
        "rotateY": 1200
      }
    }
  },
  "interactivity": {
    "detect_on": "canvas",
    "events": {
      "onhover": {
        "enable": false,
        "mode": "repulse"
      },
      "onclick": {
        "enable": false,
        "mode": "push"
      },
      "resize": true
    },
    "modes": {
      "grab": {
        "distance": 400,
        "line_linked": {
          "opacity": 1
        }
      },
      "bubble": {
        "distance": 400,
        "size": 40,
        "duration": 2,
        "opacity": 8,
        "speed": 3
      },
      "repulse": {
        "distance": 200,
        "duration": 0.4
      },
      "push": {
        "particles_nb": 4
      },
      "remove": {
        "particles_nb": 2
      }
    }
  },
  "retina_detect": true
});


