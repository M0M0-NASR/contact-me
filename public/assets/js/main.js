let form = document.getElementById("form");
let msg2 = document.getElementById("msg-2");
let err = document.getElementById("err");

err.style.display = "none";
form.onsubmit = (e) => {
  msg2.style.display = "block";
  e.preventDefault();
  let request = new XMLHttpRequest();
  request.open("POST", "http://contact-me/app/core/app.php", true);
  request.onload = () => {
    if (request.readyState == 4 && request.status == 200) {
      let res = request.response;
      let msg = document.getElementById("msg");
      console.log(res);
      res = JSON.parse(res);

      if (res[0] == "Email send") {
        msg.innerHTML = res[0];
        err.style.display = "none";
      } else {
        err.innerHTML = "";
        msg.innerHTML = res[res.length - 1];
        --res.length;
        res.forEach((e) => {
          let span = document.createElement("span");
          span.innerHTML = e;
          span.style.display = "block";
          err.appendChild(span);
        });
        err.style.display = "block";
      }
      msg.style.display = "block";
      msg2.style.display = "none";
    }
  };
  let data = new FormData(form);
  request.send(data);
};
