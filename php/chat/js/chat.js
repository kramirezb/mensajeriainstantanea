const form = document.querySelector(".typing-area"),
  //incoming_id = form.querySelector(".incoming_id").value,
  inputField = form.querySelector(".input-field"),
  sendBtn = form.querySelector("button[type='submit']"),
  fileInput = form.querySelector("#documento"),
  chatBox = document.querySelector(".chat-box");

  const incoming_id = document.querySelector(".incoming_id").value;

form.onsubmit = (e) => {
  e.preventDefault();
  sendChat();
};


inputField.focus();
inputField.onkeyup = () => {
  if (inputField.value != "") {
    sendBtn.classList.add("active");
  } else {
    sendBtn.classList.remove("active");
  }
};


/*fileInput.onchange = () => {
  if (fileInput.files.length > 0) {
    sendChat();
  }
};*/


function sendChat() {
  let formData = new FormData(form);
  fetch("servicios/insertar_chat.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.text())
    .then((data) => {
        console.log(data);
      inputField.value = "";
      fileInput.value = ""; // Clear the file input after sending
      scrollToBottom();
    })
    .catch((error) => {
      console.error("Error al enviar el archivo:", error);
    });
}

chatBox.onmouseenter = () => {
  chatBox.classList.add("active");
};

chatBox.onmouseleave = () => {
  chatBox.classList.remove("active");
};

setInterval(() => {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "servicios/obtener_chat.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        chatBox.innerHTML = data;
        if (!chatBox.classList.contains("active")) {
          scrollToBottom();
        }
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
   xhr.send("incoming_id=" + incoming_id);
}, 1000);

function scrollToBottom() {
  chatBox.scrollTop = chatBox.scrollHeight;
}
