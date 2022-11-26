@if(config('services.tawkto.src'))
<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();

    var user = @json( 
            Auth::user() ? 
                collect(
                        Auth::user()->toArray()
                )->only(['name','email'])
                : null 
    );
    Tawk_API.visitor = user;
    
    (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='{{config("services.tawkto.src")}}';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
</script>
@endif