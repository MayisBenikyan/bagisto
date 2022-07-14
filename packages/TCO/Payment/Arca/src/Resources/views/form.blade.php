<body data-gr-c-s-loaded="true" cz-shortcut-listen="true">
    You will be redirected to the Arca website in a few seconds.

    <form action="{{ $formUrl }}" id="arca_form" method="POST">
        <input value="Click here if you are not redirected within 10 seconds..." type="submit">
            <input type="hidden" name="mdOrder" value="{{ $orderId }}">
    </form>

    <script type="text/javascript">
        document.getElementById("arca_form").submit();
    </script>
</body>