function ShowMessage(text){
    console.log(text);
    const message = document.querySelector(".message")
    const p = document.querySelector(".message_text");
    p.innerHTML=text;

    message.classList.remove('hidden'); // Make sure the div is visible
    message.classList.add('fade-out'); // Start the fade-out effect

    setTimeout(function() {
      message.classList.add('hidden'); // Hide the div after 3 seconds
    }, 3000); // 3000 milliseconds = 3 seconds
  }
