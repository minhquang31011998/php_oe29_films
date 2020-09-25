<footer class="footer">
    <div class="container">
        <div class="col-12 col-sm-4 col-md-3">
            <h6 class="footer__title">{{ trans('contact') }}</h6>
            <ul class="footer__list">
                <li><a href="tel:{{ config('config.contact_phone')}}">{{ config('config.contact_phone')}}</a></li>
                <li><a href="mailto:{{ config('config.contact_email')}}">{{ config('config.contact_email')}}</a></li>
            </ul>
            <ul class="footer__social">
                <li class="facebook"><a href="#"><i class="icon ion-logo-facebook"></i></a></li>
                <li class="instagram"><a href="#"><i class="icon ion-logo-instagram"></i></a></li>
                <li class="twitter"><a href="#"><i class="icon ion-logo-twitter"></i></a></li>
                <li class="vk"><a href="#"><i class="icon ion-logo-vk"></i></a></li>
            </ul>
        </div>
        <div class="col-12">
            <div class="footer__copyright">
                <small>{{ trans('created_by') . " " . config('config.user_created_by') }}</small>
            </div>
        </div>
    </div>
</footer>
