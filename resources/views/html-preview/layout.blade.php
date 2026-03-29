<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ $container->getName() ?? ($documentTitleDefault ?? 'Documento') }}</title>
	@include('uikittemplate::css.uikitCss')

	<meta name="generator" content="Davide Bronzini">
	<link href="/kojiro/alert.css" rel="stylesheet">
	<link href="/kojiro/fontawesome.css" rel="preload" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<link href="/kojiro/kojiro.css" rel="stylesheet">
	<script type="application/json" class="joomla-script-options new">{"joomla.jtext":{"ERROR":"Error","MESSAGE":"Message","NOTICE":"Notice","WARNING":"Warning","JCLOSE":"Close","JOK":"OK","JOPEN":"Open"},"system.paths":{"root":"\/joomla","rootFull":"https:\/\/demo.yootheme.com\/joomla\/","base":"\/joomla","baseFull":"https:\/\/demo.yootheme.com\/joomla\/"},"csrf.token":"95759b0a6b9f891456e21ec9a2d65917"}</script>
	<script src="/kojiro/core.js"></script>
	<script src="/kojiro/messages.js" type="module"></script>
	<script src="/kojiro/uikit.js"></script>
	<script src="/kojiro/icons.js"></script>
	<script src="/kojiro/theme.js"></script>
	<script>window !== parent && parent.postMessage({source: "demo-iframe", href: location.href}, "https://yootheme.com");</script>
	<script>window.yootheme ||= {}; yootheme.theme = {"i18n":{"close":{"label":"Close"},"totop":{"label":"Back to top"},"marker":{"label":"Open"},"navbarToggleIcon":{"label":"Open Menu"},"paginationPrevious":{"label":"Previous page"},"paginationNext":{"label":"Next Page"},"searchIcon":{"toggle":"Open Search","submit":"Submit Search"},"slider":{"next":"Next slide","previous":"Previous slide","slideX":"Slide %s","slideLabel":"%s of %s"},"slideshow":{"next":"Next slide","previous":"Previous slide","slideX":"Slide %s","slideLabel":"%s of %s"},"lightboxPanel":{"next":"Next slide","previous":"Previous slide","slideLabel":"%s of %s","close":"Close"}}};</script>
</head>



   <body class="">
      <div class="uk-hidden-visually uk-notification uk-notification-top-left uk-width-auto">
         <div class="uk-notification-message">
            <a href="#tm-main" class="uk-link-reset">Skip to main content</a>
         </div>
      </div>
      <div class="tm-page">
         <header class="tm-header-mobile uk-hidden@s tm-header-overlay" uk-header uk-inverse="target: .uk-navbar-container; sel-active: .uk-navbar-transparent">
            <div uk-sticky show-on-up animation="uk-animation-slide-top" cls-active="uk-navbar-sticky" sel-target=".uk-navbar-container" cls-inactive="uk-navbar-transparent" tm-section-start>
               <div class="uk-navbar-container">
                  <div class="uk-container uk-container-expand">
                     <nav class="uk-navbar" uk-navbar="{&quot;align&quot;:&quot;left&quot;,&quot;container&quot;:&quot;.tm-header-mobile &gt; [uk-sticky]&quot;,&quot;boundary&quot;:&quot;.tm-header-mobile .uk-navbar-container&quot;}">
                        <div class="uk-navbar-left ">
                           <a href="https://demo.yootheme.com/joomla/themes/kojiro/" aria-label="Back to home" class="uk-logo uk-navbar-item">
                           <img src="/demo/kojiro/images/logo-mobile.svg" width="64" height="70" alt="Kojiro"><img class="uk-logo-inverse" src="/demo/kojiro/images/logo-mobile-inverted.svg" width="64" height="70" alt="Kojiro"></a>
                        </div>
                        <div class="uk-navbar-right">
                           <a uk-toggle href="#tm-dialog-mobile" class="uk-navbar-toggle">
                              <div uk-navbar-toggle-icon></div>
                           </a>
                        </div>
                     </nav>
                  </div>
               </div>
            </div>
            <div id="tm-dialog-mobile" class="uk-dropbar uk-dropbar-top" uk-drop="{&quot;flip&quot;:&quot;false&quot;,&quot;container&quot;:&quot;.tm-header-mobile &gt; [uk-sticky]&quot;,&quot;target-y&quot;:&quot;.tm-header-mobile .uk-navbar-container&quot;,&quot;mode&quot;:&quot;click&quot;,&quot;target-x&quot;:&quot;.tm-header-mobile .uk-navbar-container&quot;,&quot;stretch&quot;:true,&quot;pos&quot;:&quot;bottom-left&quot;,&quot;bgScroll&quot;:&quot;false&quot;,&quot;animateOut&quot;:true,&quot;duration&quot;:300,&quot;toggle&quot;:&quot;false&quot;}">
               <div class="tm-height-min-1-1 uk-flex uk-flex-column">
                  <div class="uk-margin-auto-bottom uk-text-center">
                     <div class="uk-grid uk-child-width-1-1" uk-grid>
                        <div>
                           <div class="uk-panel" id="module-menu-dialog-mobile">
                              <ul class="uk-nav uk-nav-primary uk-nav- uk-nav-center">
                                 <li class="item-134"><a href="/joomla/themes/kojiro/lunch">Lunch</a></li>
                                 <li class="item-135"><a href="/joomla/themes/kojiro/dinner">Dinner</a></li>
                                 <li class="item-136"><a href="/joomla/themes/kojiro/drinks">Drinks</a></li>
                                 <li class="item-132"><a href="/joomla/themes/kojiro/about">About</a></li>
                                 <li class="item-130"><a href="/joomla/themes/kojiro/news">News</a></li>
                                 <li class="item-131"><a href="/joomla/themes/kojiro/contact">Contact</a></li>
                              </ul>
                           </div>
                        </div>
                        <div>
                           <div class="uk-panel" id="module-113">
                              <div class="uk-grid tm-grid-expand uk-child-width-1-1 uk-margin-remove-vertical">
                                 <div class="uk-width-1-1">
                                    <div class="uk-margin-medium">
                                       <a class="el-content uk-button uk-button-primary" href="mailto:email@example.com">
                                       Reservation    
                                       </a>
                                    </div>
                                    <div class="uk-margin-xlarge-top uk-margin-remove-bottom" uk-scrollspy="target: [uk-scrollspy-class];">
                                       <ul class="uk-child-width-auto uk-grid-small uk-flex-inline uk-flex-middle" uk-grid>
                                          <li class="el-item">
                                             <a class="el-link uk-icon-link" href="https://www.instagram.com/"><span uk-icon="icon: instagram; width: 35; height: 35;"></span></a>
                                          </li>
                                       </ul>
                                    </div>
                                    <div class="uk-margin-top uk-margin-remove-bottom">
                                       <a class="el-link uk-link-heading" href="mailto:email@example.com">        
                                       email@example.com        
                                       </a>        
                                    </div>
                                    <div>
                                       <a class="el-link uk-link-heading" href="tel:+55512345678">        
                                       + 555 123 456 78        
                                       </a>        
                                    </div>
                                    <div class="uk-panel uk-margin">
                                       <p>Main Street<br>9876 Anytown</p>
                                       <p>Tuesday-Sunday<br>11 am - 3 pm<br>5 pm - 11 pm</p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </header>
         <header class="tm-header uk-visible@s tm-header-overlay" uk-header uk-inverse="target: .uk-navbar-container, .tm-headerbar; sel-active: .uk-navbar-transparent, .tm-headerbar">
            <div uk-sticky media="@s" show-on-up animation="uk-animation-slide-top" cls-active="uk-navbar-sticky" sel-target=".uk-navbar-container" cls-inactive="uk-navbar-transparent" tm-section-start>
               <div class="uk-navbar-container">
                  <div class="uk-container uk-container-expand">
                     <nav class="uk-navbar" uk-navbar="{&quot;align&quot;:&quot;left&quot;,&quot;container&quot;:&quot;.tm-header &gt; [uk-sticky]&quot;,&quot;boundary&quot;:&quot;.tm-header .uk-navbar-container&quot;}">
                        <div class="uk-navbar-left ">
                           <a href="https://demo.yootheme.com/joomla/themes/kojiro/" aria-label="Back to home" class="uk-logo uk-navbar-item">
                           <img src="{{ app('uikittemplate')->getLogoUrl() }}" width="103" height="110" alt="{{ app('uikittemplate')->getAppName() }}"><img class="uk-logo-inverse" src="{{ app('uikittemplate')->getLogoUrl() }}" width="103" height="110" alt="{{ app('uikittemplate')->getAppName() }}"></a>
                        </div>
                        <div class="uk-navbar-right">
                           <div class="uk-navbar-item" id="module-110">
                              <div class="uk-grid-margin uk-grid tm-grid-expand uk-child-width-1-1">
                                 <div class="uk-width-1-1">
                                    <div class="uk-margin">
                                       <a class="el-content uk-button uk-button-primary" href="mailto:email@example.com">
                                       Reservation    
                                       </a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <a uk-toggle href="#tm-dialog" class="uk-navbar-toggle">
                              <div uk-navbar-toggle-icon></div>
                           </a>
                        </div>
                     </nav>
                  </div>
               </div>
            </div>
            <div id="tm-dialog" class="uk-dropbar uk-dropbar-large uk-dropbar-top" uk-drop="{&quot;flip&quot;:&quot;false&quot;,&quot;container&quot;:&quot;.tm-header &gt; [uk-sticky]&quot;,&quot;target-y&quot;:&quot;.tm-header .uk-navbar-container&quot;,&quot;mode&quot;:&quot;click&quot;,&quot;target-x&quot;:&quot;.tm-header .uk-navbar-container&quot;,&quot;stretch&quot;:true,&quot;pos&quot;:&quot;bottom-left&quot;,&quot;bgScroll&quot;:&quot;false&quot;,&quot;animateOut&quot;:true,&quot;duration&quot;:300,&quot;toggle&quot;:&quot;false&quot;}">
               <div class="tm-height-min-1-1 uk-flex uk-flex-column">
                  <div class="uk-margin-auto-bottom tm-height-expand">
                     <div class="uk-panel" id="module-111">
                        <div class="uk-grid-margin uk-grid tm-grid-expand uk-grid-column-large uk-height-1-1" uk-grid>
                           <div class="uk-flex uk-flex-column uk-width-1-2@s uk-visible@l">
                              <div class="uk-flex-1 uk-flex uk-margin uk-visible@l">
                                 <img class="el-image" style="aspect-ratio: auto;" src="{{ app('uikittemplate')->getLogoUrl() }}" alt="Azienda" loading="lazy" width="1210" height="1000">    
                              </div>
                           </div>
                           <div class="uk-grid-item-match uk-flex-middle uk-width-1-4@s">
                              <div class="uk-panel uk-width-1-1">
                                 <nav class="uk-width-medium uk-margin-auto">
                                    <ul class="uk-margin-remove-bottom uk-nav uk-nav-primary">
                                       <li class="el-item ">
                                          <a class="el-link" href="/joomla/themes/kojiro/lunch">
                                          Lunch        
                                          </a>
                                       </li>
                                       <li class="el-item ">
                                          <a class="el-link" href="/joomla/themes/kojiro/dinner">
                                          Dinner        
                                          </a>
                                       </li>
                                       <li class="el-item ">
                                          <a class="el-link" href="/joomla/themes/kojiro/drinks">
                                          Drinks        
                                          </a>
                                       </li>
                                       <li class="el-item ">
                                          <a class="el-link" href="/joomla/themes/kojiro/about">
                                          About        
                                          </a>
                                       </li>
                                       <li class="el-item ">
                                          <a class="el-link" href="/joomla/themes/kojiro/news">
                                          News        
                                          </a>
                                       </li>
                                       <li class="el-item ">
                                          <a class="el-link" href="/joomla/themes/kojiro/contact">
                                          Contact        
                                          </a>
                                       </li>
                                    </ul>
                                 </nav>
                              </div>
                           </div>
                           <div class="uk-grid-item-match uk-flex-middle uk-width-expand@s">
                              <div class="uk-panel uk-width-1-1">
                                 <div class="uk-margin uk-width-small uk-margin-auto@s uk-margin-auto" uk-scrollspy="target: [uk-scrollspy-class];">
                                    <ul class="uk-child-width-auto uk-grid-small uk-flex-inline uk-flex-middle" uk-grid>
                                       <li class="el-item">
                                          <a class="el-link uk-icon-link" href="https://www.instagram.com/yootheme"><span uk-icon="icon: instagram; width: 35; height: 35;"></span></a>
                                       </li>
                                    </ul>
                                 </div>
                                 <div class="uk-text-emphasis uk-width-small uk-margin-auto@s uk-margin-auto">
                                    <a class="el-link uk-link-heading" href="mailto:email@example.com">        
                                    email@example.com        
                                    </a>        
                                 </div>
                                 <div class="uk-text-emphasis uk-width-small uk-margin-auto@s uk-margin-auto">
                                    <a class="el-link uk-link-heading" href="tel:+55512345678">        
                                    + 555 123 456 78        
                                    </a>        
                                 </div>
                                 <div class="uk-panel uk-text-emphasis uk-margin uk-width-small uk-margin-auto@s uk-margin-auto">123 Main Street 9876 Anytown</div>
                                 <div class="uk-panel uk-text-emphasis uk-margin uk-width-small uk-margin-auto@s uk-margin-auto">Tuesday-Sunday 11am - 3pm<br> 5pm - 11pm</div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </header>
         <main id="tm-main">
            <div id="system-message-container" aria-live="polite"></div>
            <!-- Builder #page -->
            <style class="uk-margin-remove-adjacent">#page\#0 .el-image{transform: translateY(-50%); max-width: 60vw;}#page\#1{position: relative; z-index: 1; margin-top: -100vh;}#page\#2{margin-bottom: 15vh;}#page\#3{margin-bottom: 30vh;}#page\#4 > *{position: relative; z-index: 1;}@media (min-width: 960px){#page\#5{height: 100vh;}}@media (min-width: 960px){#page\#6{margin-top: -100vh; height: 100vh;}}#page\#7 .el-image{max-width: 70vw;}@media (min-width: 640px){#page\#8{height: 400vh;}}</style>
            <div class="uk-section-default uk-inverse-light uk-section uk-padding-remove-vertical" tm-header-transparent-noplaceholder>
               <div class="uk-grid-margin uk-grid tm-grid-expand uk-child-width-1-1">
                  <div class="uk-width-1-1">
                     <div class="uk-position-z-index uk-tile uk-padding-remove" uk-height-viewport="offset-top: !*;" uk-sticky="end: !.uk-section;">
                        <picture>
                           @if($url = $destination?->getFirstMediaUrl('emotional_image'))
                              <img src="{{ $url }}" uk-cover>

                           <div class="uk-panel uk-width-1-1">            
                           </div>
                           @endif

                        </picture>
                        {{-- <video src="/demo/kojiro/images/home-hero-bg.mp4" playsinline loop muted preload="none" width="2560" uk-cover></video> --}}
                        <div class="uk-panel uk-width-1-1">            
                        </div>
                     </div>
                  </div>
               </div>
               <div class="uk-grid-margin uk-grid tm-grid-expand uk-child-width-1-1" id="page#1">
                  <div class="uk-light uk-width-1-1">
                     <div class="uk-height-viewport uk-panel uk-flex uk-flex-middle">
                        <div class="uk-panel uk-width-1-1">
                           <div class="uk-position-relative uk-margin-small uk-width-xlarge uk-margin-auto uk-text-left@l uk-text-center" uk-parallax="scale: 0.5; opacity: 1,0,0; blur: 50; easing: 0; start: 55vh + 50%" style="z-index: 1;">
                              <img class="el-image" src="{{ app('uikittemplate')->getLogoUrl() }}" alt="{{ app('uikittemplate')->getAppName() }}">
                           </div>
                           <h1 class="uk-heading-2xlarge uk-position-relative uk-margin-remove-top uk-text-center uk-visible@s" uk-parallax="scale: 0.5; opacity: 1,0,0; blur: 50; easing: 0; start: 55vh + 50%" style="z-index: 1;">
                              {{ $container->getClient()->getName() }}        
                           </h1>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="uk-container uk-container-expand uk-margin-remove-vertical" id="page#4">
                  <div class="uk-grid tm-grid-expand uk-child-width-1-1">
                     <div class="uk-light uk-width-1-1">
                        @foreach($pieces = explode('<br />', $container->description) as $piece)
                        <div class="uk-heading-xlarge uk-text-center" uk-parallax="scale: 0.5; opacity: 1,0; blur: 50; easing: 0; start: 55vh + 50%">
                           {{ $piece }}
                        </div>
                        @endforeach
                        <div class="uk-h1 uk-margin-xlarge-top uk-margin-remove-bottom uk-text-center" uk-parallax="scale: 0.5; opacity: 1,0; blur: 50; easing: 0; start: 55vh + 50%">
                           Data evento        
                        </div>
                        <div class="uk-margin-small uk-text-center" id="page#3" uk-parallax="scale: 0.5; opacity: 1,0; blur: 50; easing: 0; start: 55vh + 50%">
                           {{ $container->getStartsAt()?->format('d-m-Y H:i') }}
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="uk-section-default uk-section uk-section-xlarge-top uk-padding-remove-bottom">
               <div class="uk-container uk-container-expand">
                  <div class="uk-grid tm-grid-expand uk-child-width-1-1 uk-margin-xlarge" id="page#5">
                     <div class="js-sticky uk-width-1-1">
                        <div class="uk-panel uk-position-z-index" uk-sticky="offset: 50vh - 50%; end: !.js-sticky; media: @s;">
                           <div class="uk-panel uk-margin-remove-first-child uk-margin uk-width-large uk-margin-auto uk-text-center" uk-parallax="opacity: 1,0; blur: 50; easing: 0; media: @m; target: !.tm-grid-expand&gt;*; start: 55vh; end: 100vh">
                              <h2 class="el-title uk-heading-large uk-margin-top uk-margin-remove-bottom">

                              {{ $container->getName() }} <span class="uk-text-primary">{{ $container->getCategoryName() }}</span>
                           </h2>
                              <div class="el-content uk-panel uk-text-large uk-margin-top">
                                 @lang('products::catering.orders.baseQuantity', ['quantity' => $container->getBaseQuantity()])

                                 @foreach($container->people_coefficient as $peopleCoefficient)

                                 <br />

                                 {{ $peopleCoefficient['quantity'] }} {{ $peopleCoefficient['name'] }}

                                 @endforeach
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="uk-grid-margin uk-grid tm-grid-expand uk-grid-column-collapse" uk-grid id="page#6">
                     @foreach([
                        [
                           'title' => 'Ingredienti<br />di prima qualità',
                           'image' => 'https://geg.test/storage/1/0/0/1/1001_default.jpg',
                           'href' => 'https://www.geggastronomia.it/ingredienti-e-fornitori',
                           'subtitle' => 'Scopri di più'
                        ],
                        [
                           'title' => 'Allestimenti e<br />location ricercati',
                           'image' => 'https://geg.test/storage/1/0/0/1/1001_default.jpg',
                           'href' => 'https://www.geggastronomia.it/ingredienti-e-fornitori',
                           'subtitle' => 'Scopri di più'
                        ],
                        [
                           'title' => 'Velocità,<br />versatilità<br />e competenza',
                           'image' => 'https://geg.test/storage/1/0/0/1/1001_default.jpg',
                           'href' => 'https://www.geggastronomia.it/ingredienti-e-fornitori',
                           'subtitle' => 'Scopri di più'
                        ]] as $parameters)

                     <div class="uk-width-1-3@s">
                        <div class="uk-position-z-index uk-panel" uk-sticky="offset: 50vh - 50%; end: !.uk-section;">
                           <div class="uk-light uk-margin uk-text-center" uk-parallax="x: 100vw,0,0,-100vw; y: 0 68%,-1200; opacity: 1 70%,0; blur: 0 70%,100; easing: {{ (($loop->index + 1) / 10) }}; media: @s; target: !.uk-section; start: 62vh">
                              <a
                                 target="_blank"
                                 class="uk-transition-toggle uk-inline-clip uk-link-toggle" 
                                 href="{{ $parameters['href'] }}">
                                 <img class="el-image uk-transition-scale-up uk-transition-opaque" src="{{ $parameters['image'] }}" alt="Buffet" loading="lazy" width="840" height="1149">
                                 <div class="uk-position-top">
                                    <div class="uk-panel uk-padding-large uk-margin-remove-first-child">
                                       <div class="el-title uk-heading-medium uk-margin-top uk-margin-remove-bottom">   {!! $parameters['title'] !!}
                                       </div>
                                       <div class="uk-margin-small-top">
                                          <div class="el-link uk-button uk-button-text uk-button-large">{{ $parameters['subtitle'] }}</div>
                                       </div>
                                    </div>
                                 </div>
                              </a>
                           </div>
                        </div>
                     </div>
                     @endforeach

                  </div>
               </div>
            </div>
            <div class="uk-section-default uk-section uk-section-large">
               <div class="uk-container">
                  <div class="uk-grid-margin uk-grid tm-grid-expand" uk-grid>
                     <div class="uk-grid-item-match uk-flex-middle uk-width-1-2@s">
                        <div class="uk-panel uk-width-1-1">
                           <div class="uk-panel uk-margin-remove-first-child uk-position-relative uk-margin uk-width-large" style="z-index: 1;" uk-scrollspy="target: [uk-scrollspy-class];">
                              <h2 class="el-title uk-heading-large uk-margin-top uk-margin-remove-bottom">
                                 Termini e condizioni
                              </h2>
                              <div class="el-content uk-panel uk-text-large uk-margin-medium-top">Le immagini sono a puro scopo illustrativo. Con l\'accettazione dell\'ordine si accettano incondizionatamente le condizioni e i termini di pagamento.</div>
                              <div class="uk-margin-large-top"><a href="https://www.geggastronomia.it/termini-di-accettazione-preventivo" class="el-link uk-button uk-button-text">Leggi i termini e le condizioni completi</a></div>
                           </div>
                        </div>
                     </div>
                     <div class="uk-width-1-2@s">
                        <div class="uk-margin" uk-parallax="y: 100,-100; opacity: 1 70%,0; blur: 0 70%,100; easing: 0; media: @s; start: 50vh">
                           <img class="el-image" src="{{ app('uikittemplate')->getLogoUrl() }}" alt="Chi siamo" loading="lazy" height="100">    
                        </div>
                     </div>
                  </div>
                  <div class="uk-grid-margin uk-grid tm-grid-expand uk-child-width-1-1">
                     <div class="uk-grid-item-match uk-width-1-1">
                        <div class="uk-panel uk-width-1-1">
                           <div class="uk-position-absolute uk-width-1-1 uk-text-center" id="page#7" uk-parallax="opacity: 1 70%,0; blur: 0 70%,100; easing: 0; media: @s" style="right: -30px; bottom: -6vh;" uk-scrollspy="target: [uk-scrollspy-class];">
                              <img class="el-image uk-text-primary" src="/demo/kojiro/images/about-signature.svg" alt loading="lazy" width="850" height="312" uk-svg="stroke-animation: true; attributes: uk-scrollspy-class:uk-animation-stroke">    
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>

			@yield('content')

			@include('products::html-preview._footerImages')


         </main>

		@include('products::html-preview._footerData')

      </div>
   </body>

</html>
