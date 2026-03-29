<li class="el-item">
   <div class="el-title uk-margin-remove uk-text-large" uk-leader>
      {{ $row->getPdfDescriptionString() }}
   </div>
   <div uk-grid>
      <div class="uk-width-expand">
         @if(count($allergens = $row->getSellable()?->getTarget()?->getAllergensList()))
         <strong>Allergeni:</strong> 
            @foreach($allergens as $allergen)
               {{ $allergen->getName() }} @if(! $loop->last) - @endif
            @endforeach
         @endif
      </div>
      <div class="uk-width-auto">
         <div class="el-meta uk-text-large uk-text-emphasis">
            {!! $row->getPdfDescriptionCost() !!} €
         </div>         
      </div>      
   </div>
</li>
