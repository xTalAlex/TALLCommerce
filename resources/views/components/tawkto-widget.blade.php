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
    s1.src='https://embed.tawk.to/62df075754f06e12d88b4c6b/1g8rj9c0p';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
    })();
</script>