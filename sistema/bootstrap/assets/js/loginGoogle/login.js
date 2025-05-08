
function aqui(response)
{
     // decodeJwtResponse() is a custom function defined by you
     // to decode the credential response.
console.log(response);


     const responsePayload = decodeJWT(response.credential);

     console.log("ID: " + responsePayload.sub);
     console.log('Full Name: ' + responsePayload.name);
     console.log('Given Name: ' + responsePayload.given_name);
     console.log('Family Name: ' + responsePayload.family_name);
     console.log("Image URL: " + responsePayload.picture);
     console.log("Email: " + responsePayload.email);
}

function decodeJWT(token) {
    const base64Url = token.split('.')[1];
    const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    const jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
    return JSON.parse(jsonPayload);
}
function signOut()
{
    //gapi.auth2.getAuthInstance().disconnect();

    let auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function ()
    {
        auth("logout");
        window.location.href = "../login";
    });
}



function auth(action, profile = null)
{
    let data = { UserAction : action };

    if(profile)
    {
         data =
            {
                UserName : profile.getGivenName(),
                UserLastName : profile.getFamilyName(),
                UserEmail : profile.getEmail(),
                UserAction : action
            };
    }

console.log(data);
return false;

    $.ajax(
        {
            type: "POST",
            url: "../users/user.php",
            data: data,
            success: function (data)
            {
                let user = JSON.parse(data);
                console.log(data);
                if(user.logged)
                {
                    $('#user_given_name').text( profile.getGivenName());
                    $('#user_last_name').text( profile.getFamilyName());
                    $('#user_email').text( profile.getEmail());
                    $('#user_profile').attr( "src", profile.getImageUrl());

                    if(document.URL === "http://itic.tutoriales.com/google/login/")
                    {
                        window.location.href = "../home";
                    }
                }
                else
                {
                    alert("La cuenta no esta registrada");
                    signOut();

                }
            }
        }
    )

}