<div class="uk-width-1-3@l uk-visible@l">
   <div class="uk-margin uk-width-xlarge uk-margin-auto-left uk-text-center uk-visible@l">
      <div class="uk-grid uk-child-width-1-1 uk-child-width-1-1@m" uk-grid>


      @foreach($phases as $phase)

         @foreach($productRows as $row)

            @if($row->hasPhase($phase['name']))
               @if($img = $row->getPdfImage())

               <div>
                  <div class="el-item uk-light uk-transition-toggle uk-inline-clip" tabindex="0">
                     <img class="el-image uk-transition-opaque" src="{{ $img }}" alt="{{ $row->getName() }}" width="1000" height="1367">
                  </div>
               </div>

               @endif

            @endif

         @endforeach

      @endforeach

      @foreach($productRows as $row)

         @if($row->hasEmptyPhase())
            @if($img = $row->getPdfImage())

            <div>
               <div class="el-item uk-light uk-transition-toggle uk-inline-clip" tabindex="0">
                  <img class="el-image uk-transition-opaque" src="{{ $img }}" alt="{{ $row->getName() }}" width="1000" height="1367">
               </div>
            </div>

            @endif

         @endif

      @endforeach


      </div>
   </div>
</div>
