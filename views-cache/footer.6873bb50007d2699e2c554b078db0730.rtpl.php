<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="footer-top-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="footer-about-us">
                    <h2>WPL Store</h2>
                    <p></p>
                    <div class="footer-social">
                        <a href="#" target="_blank"><i class="fa fa-facebook"></i></a>
                        <a href="#" target="_blank"><i class="fa fa-twitter"></i></a>
                        <a href="#" target="_blank"><i class="fa fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6">
                <div class="footer-menu">
                    <h2 class="footer-wid-title">Navegação </h2>
                    <ul>
                        <li><a href="/profile">Minha Conta</a></li>
                        <li><a href="/profile/orders">Meus Pedidos</a></li>
                        <li><a href="#">Lista de Desejos</a></li>
                    </ul>                        
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6">
                <div class="footer-menu">
                    <h2 class="footer-wid-title">Categorias</h2>
                    <ul>
                        <?php require $this->checkTemplate("categories-menu");?>
                    </ul>                        
                </div>
            </div>
            
            <div class="col-md-3 col-sm-6">
                <div class="footer-newsletter">
                    <h2 class="footer-wid-title">Newsletter</h2>
                    <p>Inscreva-se e receba ofertas exclusivas diretamente do seu email!</p>
                    <div class="newsletter-form">
                        <form action="#">
                            <input type="email" placeholder="Digite seu email">
                            <input type="submit" value="Inscreva-se">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- End footer top area -->

<div class="footer-bottom-area">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="copyright">
                    <p>&copy; 2020 WPL Store. <a href="www.wplecommerce" target="_blank">wplecommerce.com</a></p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="footer-card-icon">
                    <i class="fa fa-cc-discover"></i>
                    <i class="fa fa-cc-mastercard"></i>
                    <i class="fa fa-cc-paypal"></i>
                    <i class="fa fa-cc-visa"></i>
                </div>
            </div>
        </div>
    </div>
</div> <!-- End footer bottom area -->

<!-- Latest jQuery form server -->
<script src="https://code.jquery.com/jquery.min.js"></script>

<!-- Bootstrap JS form CDN -->
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

<!-- jQuery sticky menu -->
<script src="/res/site/js/owl.carousel.min.js"></script>
<script src="/res/site/js/jquery.sticky.js"></script>

<!-- jQuery easing -->
<script src="/res/site/js/jquery.easing.1.3.min.js"></script>

<!-- Main Script -->
<script src="/res/site/js/main.js"></script>

<!-- Slider -->
<script type="text/javascript" src="/res/site/js/bxslider.min.js"></script>
<script type="text/javascript" src="/res/site/js/script.slider.js"></script>
</body>
</html>