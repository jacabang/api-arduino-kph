<html>
<head>
    <link href="{{asset('swagger/style.css')}}" rel="stylesheet">
    <style>
        @media (min-width: 992px)
        section {
             padding-top: 0rem; 
             padding-bottom: 0rem; 
        }
        section {
/*            position: relative;*/
             padding-top: 0rem; 
             padding-bottom: 0rem; 
        }
    </style>
</head>
<body>
<div id="swagger-ui"></div>
<script src="{{asset('swagger/jquery-2.1.4.min.js')}}"></script>
<script src="{{asset('swagger/swagger-bundle.js')}}"></script>
<script type="application/javascript">
    const ui = SwaggerUIBundle({
        url: "{{ asset('swagger/swagger.yaml') }}",
        dom_id: '#swagger-ui',
    });
</script>
</body>
</html>
