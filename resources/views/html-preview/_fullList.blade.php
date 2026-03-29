<div class="uk-width-expand@l">
   <div class="uk-panel uk-margin">

      @foreach($phases as $phase)

      <div class="uk-grid tm-grid-expand uk-child-width-1-1 uk-margin-xlarge">
         <div class="uk-width-1-1">
            <h2 class="uk-heading-small uk-margin-medium" id="{{ Str::slug($phase['name']) }}">
               {{ ucfirst($phase['name']) }}
            </h2>
            <ul class="uk-list uk-list-large uk-margin-medium">
               @foreach($productRows as $row)
                  @if($row->hasPhase($phase['name']))

                     @include('products::html-preview._singleItem')

                  @endif
               @endforeach
            </ul>
         </div>
      </div>

      @endforeach

      @php

      $printedIntestation = false;

      @endphp

      @foreach($productRows as $row)
         @if($row->hasEmptyPhase())
            @if(! $printedIntestation)
      <div class="uk-grid tm-grid-expand uk-child-width-1-1 uk-margin-xlarge">
         <div class="uk-width-1-1">
            <h2 class="uk-heading-small uk-margin-medium" id="vari">
               Vari ed eventuali
            </h2>
            <ul class="uk-list uk-list-large uk-margin-medium">

               @php
                  $printedIntestation = true;
               @endphp
            @endif

            @include('products::html-preview._singleItem')

         @endif
      @endforeach

      @if($printedIntestation)
            </ul>
         </div>
      </div>
      @endif

   </div>

</div>
