var textarea = document.querySelector('textarea');

textarea.addEventListener('keydown', autosize);

function autosize(){
  var el = this;
  setTimeout(function(){
    el.style.cssText = 'height:auto; padding:0';
    el.style.cssText = 'height:' + (el.scrollHeight+20) + 'px';
  },0);
}

var input = document.getElementById('textareaLang');
input.addEventListener("keyup", function(event) {
    event.preventDefault();
    if (event.keyCode === 13) {
        document.getElementById("translate").click();
    }
});

document.getElementById("btn-ind").addEventListener("click", function(){
  //var xClass = document.getElementById("btn-ind").className;
  var x = document.getElementById("btn-ind");
  // var y = document.getElementById("demo");
  var x2 = document.getElementById("btn-ind2");
  var z2 = document.getElementById("btn-ar2");
  
  document.getElementById("translate").name = "translateInd";
  document.getElementById("btn-ind").className = "montserrat button is-primary ";
  document.getElementById("btn-ar").className = "montserrat button";

  z2.classList.add("is-primary");
  x2.classList.remove("is-primary");
  // y.innerHTML = document.getElementById("textareaLang").name;
});

document.getElementById("btn-ar").addEventListener("click", function(){
  //var zClass = document.getElementById("btn-ar").className;
  // var y = document.getElementById("demo");
  var z = document.getElementById("btn-ar");
  var x2 = document.getElementById("btn-ind2");
  var z2 = document.getElementById("btn-ar2");
 
  z.className = "montserrat button is-primary";
  document.getElementById("translate").name = "translateArb";
  document.getElementById("btn-ind").className = "montserrat button";

  x2.classList.add("is-primary");
  z2.classList.remove("is-primary");

  // y.innerHTML = document.getElementById("textareaLang").name;
});

document.getElementById("btn-ind2").addEventListener("click", function(){
  // var x = document.getElementById("btn-ind");
  // var y = document.getElementById("demo");
  var x = document.getElementById("btn-ind");
  var z = document.getElementById("btn-ar");
 
  document.getElementById("translate").name = "translateInd";
  document.getElementById("btn-ind2").className = "montserrat button is-primary";
  document.getElementById("btn-ar2").className = "montserrat button";

  z.classList.add("is-primary");
  x.classList.remove("is-primary");
});

document.getElementById("btn-ar2").addEventListener("click", function(){
  var z = document.getElementById("btn-ar");
  var x = document.getElementById("btn-ind");
 
  document.getElementById("btn-ar2").className = "montserrat button is-primary";
  document.getElementById("translate").name = "translateArb";
  document.getElementById("btn-ind2").className = "montserrat button";

  x.classList.add("is-primary");
  z.classList.remove("is-primary");
});
