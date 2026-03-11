@php
	$company = config('pdf.company', []);
	$email = $company['email'] ?? env('COMPANY_EMAIL', 'info@geggastronomia.it');
	$phone = $company['phone'] ?? env('COMPANY_PHONE', '3407977995');
	$fax = $company['fax'] ?? env('COMPANY_FAX', '0422774513');
	$address = $company['address'] ?? env('COMPANY_ADDRESS', 'Via Postumia 133, 31050 Ponzano Veneto (TV)');
	$name = $company['name'] ?? env('COMPANY_NAME', 'G&G Gastronomia di Berton Giacomo');
	$piva = $company['piva'] ?? env('COMPANY_PIVA', '04486340286');
	$website = $company['website'] ?? env('COMPANY_WEBSITE', 'www.geggastronomia.it');
@endphp
<footer>
   <!-- Builder #footer -->
   <div class="uk-section-default uk-section uk-section-large">
      <div class="uk-container uk-container-xlarge">
         <div class="uk-grid tm-grid-expand uk-child-width-1-1 uk-margin-small">
            <div class="uk-width-1-1">
               <hr>
               <div class="uk-margin-medium uk-visible@s">
                  <div class="uk-grid uk-child-width-1-1 uk-child-width-1-2@l uk-grid-column-large uk-grid-divider uk-grid-match" uk-grid>
                     <div>
                        <div class="el-item uk-panel uk-margin-remove-first-child">
                           <div class="el-title uk-heading-small uk-link-heading uk-margin-top uk-margin-remove-bottom">                        <a href="mailto:{{ $email }}">{{ $email }}</a>                    </div>
                        </div>
                     </div>
                     <div>
                        <div class="el-item uk-panel uk-margin-remove-first-child">
                           <div class="el-title uk-heading-small uk-link-heading uk-margin-top uk-margin-remove-bottom">                        <a href="tel:+39{{ $phone }}">+39 {{ $phone }}</a>                    </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="uk-margin-medium uk-hidden@s">
                  <div class="uk-grid uk-child-width-1-1 uk-child-width-1-2@l uk-grid-column-large uk-grid-divider uk-grid-match" uk-grid>
                     <div>
                        <div class="el-item uk-panel uk-margin-remove-first-child">
                           <div class="el-title uk-h2 uk-link-reset uk-margin-top uk-margin-remove-bottom">                        <a href="mailto:{{ $email }}">{{ $email }}</a>                    </div>
                        </div>
                     </div>
                     <div>
                        <div class="el-item uk-panel uk-margin-remove-first-child">
                           <div class="el-title uk-h2 uk-link-reset uk-margin-top uk-margin-remove-bottom">                        <a href="tel:+39{{ $phone }}">+39 {{ $phone }}</a>                    </div>
                        </div>
                     </div>
                  </div>
               </div>
               <hr>
            </div>
         </div>
         <div class="uk-grid tm-grid-expand uk-grid-large uk-margin-xlarge" uk-grid>
            <div class="uk-width-1-2@m">
               <div class="uk-panel uk-margin uk-width-xlarge">
                  <div class="uk-grid-column-large uk-flex-middle" uk-grid>
                     <div class="uk-width-auto@l">
                        <a href="https://{{ $website }}">
                        <img class="el-image" src="/demo/kojiro/images/logo.svg" alt loading="lazy" width="110" height="117">
                        </a>
                     </div>
                     <div class="uk-width-expand uk-margin-remove-first-child">
                        <div class="el-content uk-panel uk-text-large uk-margin-top">{{ $name }} - Catering e Eventi. La soluzione perfetta per matrimoni, coffee break e feste private di qualità.</div>
                     </div>
                  </div>
               </div>
               <div class="uk-margin-large">
                  <div class="uk-flex-middle uk-grid-column-large uk-grid-row-small uk-child-width-auto" uk-grid>
                     <div class="el-item">
                        <a class="el-content uk-button uk-button-text uk-button-large" href="mailto:{{ $email }}">
                        Richiedi preventivo
                        </a>
                     </div>
                     <div class="el-item">
                        <a class="el-content uk-button uk-button-text uk-button-large" href="https://{{ $website }}/contatti">
                        Contattaci
                        </a>
                     </div>
                  </div>
               </div>
            </div>
            <div class="uk-width-1-2@m">
               <div class="uk-margin uk-width-2xlarge">
                  <div class="uk-grid uk-child-width-auto uk-child-width-1-2@s uk-grid-large uk-grid-match" uk-grid>
                     <div>
                        <div class="el-item uk-panel uk-margin-remove-first-child">
                           <div class="el-title uk-h5 uk-text-muted uk-margin-top uk-margin-remove-bottom">                        Visit Us                    </div>
                           <div class="el-content uk-panel uk-h3 uk-margin-small-top uk-margin-remove-bottom">{{ $address }}</div>
                        </div>
                     </div>
                     <div>
                        <div class="el-item uk-panel uk-margin-remove-first-child">
                           <div class="el-title uk-h5 uk-text-muted uk-margin-top uk-margin-remove-bottom">                        Contatti                    </div>
                           <div class="el-content uk-panel uk-h3 uk-margin-small-top uk-margin-remove-bottom">Tel e Fax: {{ $fax }}<br>Mobile: {{ $phone }}<br>P.IVA {{ $piva }}</div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</footer>
