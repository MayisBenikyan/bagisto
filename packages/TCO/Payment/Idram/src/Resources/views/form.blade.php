<?php $idram = app('TCO\Payment\Idram\Payment\Idram') ?>

<body data-gr-c-s-loaded="true" cz-shortcut-listen="true">
    You will be redirected to the PayPal website in a few seconds.

    <form action="{{ $idram->getIdramUrl() }}" id="idram_form" method="POST">
        <input value="Click here if you are not redirected within 10 seconds..." type="submit">
        @foreach ($idram->getFormFields() as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endforeach
    </form>

    <script type="text/javascript">
        document.getElementById("idram_form").submit();
    </script>
</body>