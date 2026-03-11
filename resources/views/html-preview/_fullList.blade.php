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
                     <li class="el-item">
                        <div class="uk-child-width-auto uk-grid-column-medium uk-flex-bottom" uk-grid>
                           <div class="uk-width-expand">
                              <div class="el-title uk-margin-remove uk-text-large" uk-leader>{{ $row->getQuantity() }} - {{ $row->getPdfDescriptionString() }}</div>
                           </div>
                           <div>
                              <div class="el-meta uk-text-large uk-text-emphasis">{!! $row->getPdfDescriptionCost() !!}</div>
                           </div>
                        </div>
                     </li>
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

               @php
                  $printedIntestation = true;
               @endphp

            @endif

            <li class="el-item">
               <div class="uk-child-width-auto uk-grid-column-medium uk-flex-bottom" uk-grid>
                  <div class="uk-width-expand">
                     <div class="el-title uk-margin-remove uk-text-large" uk-leader>{{ $row->getPdfDescriptionString() }}</div>
                  </div>
                  <div>
                     <div class="el-meta uk-text-large uk-text-emphasis">{!! $row->getPdfDescriptionCost() !!}</div>
                  </div>
               </div>
            </li>

         @endif
      @endforeach

      @if($printedIntestation)
            </ul>
         </div>
      </div>
      @endif

   </div>
</div>
