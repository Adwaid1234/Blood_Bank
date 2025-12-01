// Example basic localStorage handling

document.addEventListener("DOMContentLoaded", () => {
    const donorForm = document.getElementById("donorForm");
    const hospitalForm = document.getElementById("hospitalForm");
    const requestForm = document.getElementById("requestForm");
    const adminLoginForm = document.getElementById("adminLoginForm");

    if (donorForm) {
        donorForm.addEventListener("submit", function(e) {
            if(!allFeildsValid()){
            e.preventDefault();
            alert("Donor registeration failed!");
            }
            //donorForm.reset();
        });
    }

    if (hospitalForm) {
        hospitalForm.addEventListener("submit",function(e) {
            if(!allFeildsValid()){
            e.preventDefault();
            alert("Hospital registeration failed!");
            }
            
            //hospitalForm.reset();
        });
    }

    if (requestForm) {
        requestForm.addEventListener("submit", function(e) {
            if(!allFeildsValid()){
            e.preventDefault();
            alert("Reqeust failed!");
            }
            
            //requestForm.reset();
        });
    }

});

//donar password validation
document.addEventListener('DOMContentLoaded',function(){

const password = document.getElementById("password");
const message = document.getElementById("message");
const pswmessage = document.getElementById("password-message");
const items = document.getElementsByClassName('password-message-item');

function validatePassword() {
    const value = password.value;
    const rules = [
        /[a-z]/.test(value),       // Lowercase
        /[A-Z]/.test(value),       // Uppercase
        /[0-9]/.test(value),       // Number
        /[^A-Za-z0-9]/.test(value), //special character
        value.length >= 8          // Minimum length
    ];
    for (let i = 0; i < items.length; i++) {
        items[i].classList.toggle('valid', rules[i]);
        items[i].classList.toggle('invalid', !rules[i]);
    }
    validateConfirmPassword();
    submitBtn.disabled = !(rules.every(Boolean) && passwordsMatch());
}



function validateConfirmPassword() {
    if(passwordsMatch()) {
        message.textContent = "Passwords match";
        message.style.color = "green";
    } else {
        message.textContent = "Passwords do not match";
        message.style.color = "red";
    }
}

password.addEventListener('input', validatePassword);
console.log('initial password value:',password.value);

window.onload = function() {
    submitBtn.disabled = true;
    validatePassword();
    validateConfirmPassword();
};
password.addEventListener('focus',function(){
    pswmessage.style.display='block';
});
password.addEventListener('blur',function(){
    pswmessage.style.display='none';
});

});

//hospital password validation
document.addEventListener('DOMContentLoaded',function(){

const password = document.getElementById("password");
const confirmPassword = document.getElementById("confirmPassword");
const message = document.getElementById("message");
const pswmessage = document.getElementById("password-message");
const items = document.getElementsByClassName('password-message-item');

function validatePassword() {
    const value = password.value;
    const rules = [
        /[a-z]/.test(value),       // Lowercase
        /[A-Z]/.test(value),       // Uppercase
        /[0-9]/.test(value),       // Number
        /[^A-Za-z0-9]/.test(value), //special character
        value.length >= 8          // Minimum length
    ];
    for (let i = 0; i < items.length; i++) {
        items[i].classList.toggle('valid', rules[i]);
        items[i].classList.toggle('invalid', !rules[i]);
    }
    validateConfirmPassword();
    submitBtn.disabled = !(rules.every(Boolean) && passwordsMatch());
}

function passwordsMatch() {
    return password.value && confirmPassword.value === password.value;
}

function validateConfirmPassword() {
    if(passwordsMatch()) {
        message.textContent = "Passwords match";
        message.style.color = "green";
    } else {
        message.textContent = "Passwords do not match";
        message.style.color = "red";
    }
}

password.addEventListener('input', validatePassword);
confirmPassword.addEventListener('input', validateConfirmPassword);
console.log('initial password value:',password.value);

window.onload = function() {
    submitBtn.disabled = true;
    validatePassword();
    validateConfirmPassword();
};
passwoed.addEventListener('focus',function(){
    pswmessage.style.display='block';
});
passwoed.addEventListener('blur',function(){
    pswmessage.style.display='none';
});

});