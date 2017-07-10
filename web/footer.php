
    
<!--Footer-->
    
    
<div id="footer">
<footer id="footer-main">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <p>Copyright &copy; 2017. Coded by <a href="https://www.facebook.com/Eidarous">Eidarous</a></p>
                </div>
            </div>
        </div>
    </footer>
   
   </div>
    
    <script>

        $(function () {

            var links = $('.sidebar-links > div');

            links.on('click', function () {

                links.removeClass('selected');
                $(this).addClass('selected');

            });
        });

    </script>


    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>
