const urlInp = document.getElementById('urlinput');
const shortenButton = document.getElementById('urlinputbtn');

shortenButton.addEventListener('click',function (){
    if (shortenButton.classList.contains('copy')){
        urlInp.select();
        urlInp.setSelectionRange(0,9999);
        document.execCommand("copy");
        alert("Copied the text: " + urlInp.value)
    }
});
function createShortUrl(){
    if (!shortenButton.classList.contains('copy')){
        const formError = document.getElementsByClassName('form-error')[0];

        formError.innerHTML = '';
        formError.classList.remove('show');

        if (urlInp.value.trim().length > 0){
            postUrl();
        }else{
            formError.innerHTML = 'Area empty. Please input a link';
            formError.classList.add('show');
        }
    }

    return false;
}

function postUrl(){
    const formData = new FormData;
    const formError = document.getElementsByClassName('form-error')[0];
    const formSuccess = document.getElementsByClassName('form-success')[0];


    formData.append('url',urlInp.value);

    fetch('/url/create', {
        method: 'POST',
        // headers:{
        //     'Accept': 'application/json',
        //     'Content-Type': 'application/json'
        // }
        body: formData
    }).then(resp => resp.json())
        .then(resp => {
            if (resp.error == true){
                formError.innerHTML = resp.errorMessage.url;
                formError.classList.add('show');
            }else{
                // formSuccess.innerHTML = 'url created successfuly';
                urlInp.value = resp.response;
                shortenButton.value = 'Copy';
                shortenButton.classList.add('copy');
                shortenButton.style.backgroundColor = "green";
            }
            console.log(resp);
        }).catch(err=>{
        console.log(err);
    })
}