<div class="container-identity-connect">
    <button class="btn-identity-connect" id="iamxLoginButton" onclick="createDID()">
        Create IAMX DID
    </button>

    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script type="text/javascript">

        function createDID() {
            window.open("https://kyc.iamx.id");
        }

        function connectIdentity() {
            axios.get("/iamx/get_identity_scope")
                .then((response) => {
                    window.IAMX.connect(response.data);
                });
        }

        function disconnectIdentity() {
            axios.get("/iamx/disconnect_identity")
                .then((response) => {
                    document.getElementById("iamxLoginButton").innerHTML = "Login using IAMX Identity";
                    document.getElementById("iamxLoginButton").onclick = connectIdentity;
                });
        }

        window.addEventListener("load", function () {
            axios.get("/iamx/get_identity_scope")
                .then((response) => {
                    window.IAMX.isReady(response.data);
                });
        })

        window.addEventListener("message", function (event) {
            if (event.data.type === "FROM_IAMX") {
                console.log(event.data.msg);
                if (event.data.msg === "isReady") {
                    document.getElementById("iamxLoginButton").innerHTML = "Login using IAMX Identity";
                    document.getElementById("iamxLoginButton").onclick = connectIdentity;
                } else if (event.data.msg === "discloseCredentials") {
                    axios.post("/iamx/connect_identity", {
                        data: event.data.data,
                    })
                        .then(response => {
                            document.getElementById("iamxLoginButton").innerHTML = "Logout";
                            document.getElementById("iamxLoginButton").onclick = disconnectIdentity;
                            if (response.data.redirect_url) {
                                window.location.href = response.data.redirect_url;
                            }
                        });
                }
            }
        });

    </script>
</div>
