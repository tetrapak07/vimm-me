    
    &nbsp;
    <a href="#" class="rating" onclick="return false;" data-action="plus" data-id="{{$object->rating_id}}" style="color: greenyellow;cursor: pointer;text-decoration: none;"><img src="{{asset('/css/thumbs-up-icon.png')}}"  width="20" height="20" alt="Нравится">
        <span>
        {{(($object->rating->rating_plus)&&(isset($object->rating->rating_plus))&&($object->rating->rating_plus!='')) ? $object->rating->rating_plus : '0'}}
        </span>
    </a>
    &nbsp;
    <a href="#" class="rating" onclick="return false;" data-action="minus" data-id="{{$object->rating_id}}" style="cursor: pointer;text-decoration: none;"><img src="{{asset('/css/thumbs-down-icon.png')}}"  width="20" height="20" alt="Не нравится">
        <span>
        {{(isset($object->rating->rating_minus)&&($object->rating->rating_minus)&&($object->rating->rating_minus!='')) ? $object->rating->rating_minus : '0'}}
        </span>
    </a>
    &nbsp;
    
    

