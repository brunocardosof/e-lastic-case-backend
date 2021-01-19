const trackCode = document.getElementById("trackCode");
const buttonTrack = document.getElementById("buttonTrack");
const buttonSendEmail = document.getElementById("buttonSendEmail");
const base_url = "http://localhost/e-lastic-case-php/";
const cardTrack = document.getElementById("cardTrack");
const cardEmailSender = document.getElementById("cardEmailSender");
const subTitleCardEmailSender = document.getElementById("cardEmailSender");
//inputs email
const subject = document.getElementById("subject");
const recipient_name = document.getElementById("recipient_name");
const recipient_email = document.getElementById("recipient_email");

buttonTrack.onclick = () => {
  fetchTrackObject()
    .then((result) => result.json())
    .then((result) => {
      if (result) { 
        Swal.fire({
          icon: 'success',
          title: 'Email enviado com sucesso!!',
          showConfirmButton: false,
          timer: 1500
        })
      }
    });
};
async function fetchTrackObject() {
  const formData = new FormData();
  formData.append("trackCode", trackCode.value);
  formData.append("subject", subject.value);
  formData.append("recipient_name", recipient_name.value);
  formData.append("recipient_email", recipient_email.value);
  return await fetch(`${base_url}trackAndEmail`, {
    method: 'POST',
    body: formData
  });
}

