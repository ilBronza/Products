<div class="js-sticky uk-width-1-5@xl uk-visible@xl" style="align-self: stretch;">
   <div class="uk-panel uk-position-z-index" uk-sticky="offset: 50vh - 50%; end: !.js-sticky; media: @m;">
      <nav class="uk-visible@xl">
         <ul class="uk-margin-remove-bottom uk-nav uk-nav-primary" uk-scrollspy-nav="closest: li; scroll: true;">
            @foreach($phases as $phase)
            <li class="el-item ">
               <a class="el-link" href="#{{ Str::slug($phase['name']) }}">
                  {{ ucfirst($phase['name']) }}
               </a>
            </li>
            @endforeach
         </ul>
      </nav>

      <div class="uk-margin-medium uk-visible@xl">
         <a class="el-content uk-button uk-button-secondary uk-button-large" href="#" uk-scroll>
            Conferma
         </a>
      </div>

   </div>
</div>