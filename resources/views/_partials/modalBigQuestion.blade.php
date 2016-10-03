<div class="modal" id="make-question" tabindex="-1" role="dialog" aria-labelledby="make-question" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">{{trans('messages.new-video-question')}}</h4>
      </div>
      <div class="modal-body">
         <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          
           <div class="form-group titleVideo" style="width:95%" data-id="0">
            <label class="control-label" for="videoTitle">{{trans('messages.video-title')}}</label>
            <input class="form-control videoTitle" data-id="0" type="text" value="" placeholder="{{trans('messages.video-title-placeh')}}">
          </div>
          
         
          <div class="form-group">
            <label class="control-label">{{trans('messages.video-voice')}}:</label>
            <label class="radio-inline"><input type="radio" name="voiceChange" checked="checked" value="0">{{trans('messages.voice-normal')}}</label>
            <label class="radio-inline"><input type="radio" name="voiceChange" value="1">{{trans('messages.voice-down')}}</label>
            <label class="radio-inline"><input type="radio" name="voiceChange" value="2">{{trans('messages.voice-up')}}</label>
	  </div>
             
           <div class="form-group">
               
            <label class="control-label">{{trans('messages.video-filter')}}:</label>
            <label class="radio-inline"><input type="radio" name="videoChange" checked="checked" value="0">{{trans('messages.video-normal')}}</label>
            <label class="radio-inline"><input type="radio" name="videoChange" value="1">{{trans('messages.video-negate')}}</label>
             <label class="radio-inline"><input type="radio" name="videoChange" value="2">{{trans('messages.video-blur')}}</label>
             
              
              
                  <select class="form-control" id="moreFiltres" name="videoChangeMore" style="width:95%">
                    <option value="">{{trans('messages.video-more')}}</option>
                    <option value="bw0r">{{trans('messages.video-black-white')}}</option>
                    <option value="cartoon">{{trans('messages.video-cartoon')}}</option>
                    <option value="distort0r">{{trans('messages.video-distortor')}}</option>
                    <option value="invert0r">{{trans('messages.video-invertor')}}</option>
                    <option value="pixeliz0r">{{trans('messages.video-pixelizer')}}</option>
                    <option value="sobel">{{trans('messages.video-sobel')}}</option>
                    <option value="twolay0r">{{trans('messages.video-twolayer')}}</option>
                    
                    <option value="baltan">{{trans('messages.video-baltan')}}</option>
                    <option value="nervous">{{trans('messages.video-nervous')}}</option>
                    <option value="scanline0r">{{trans('messages.video-scanliner')}}</option>
                    <option value="threelay0r">{{trans('messages.video-threelayer')}}</option>
                    <option value="luminance">{{trans('messages.video-luminance')}}</option>
                   <!-- <option value="glow">{{trans('messages.video-glow')}}</option>
                    <option value="xpro">{{trans('messages.video-xpro')}}</option>
                    <option value="sepia">{{trans('messages.video-sepia')}}</option>
                    <option value="heat">{{trans('messages.video-heat')}}</option>
                    <option value="red_green">{{trans('messages.video-red-green')}}</option>
                    <option value="old_photo">{{trans('messages.video-old-photo')}}</option>
                    <option value="xray">{{trans('messages.video-xray')}}</option>
                    <option value="esses">{{trans('messages.video-esses')}}</option>
                    <option value="yellow_blue">{{trans('messages.video-yellow-blue')}}</option>-->
                  </select>
  
	  </div>
			
			<section class="experiment recordrtc" data-id="0">
			 <button type="button" id="record" class="btn btn-primary" style="padding:5px;" data-id="">{{trans('messages.start-recording')}}</button>
            <span class="timer" data-id="0" style="display:none">
            
            <span class="timerInp" data-id="0">12</span>с. -
            <span class="tooltipMy"  data-id="0" style="color:greenyellow">{{trans('messages.speak')}}</span>
            </span>
            <button type="button" class="btn btn-primary publishQuestion" style="display: none;padding:5px;" data-id="0">{{trans('messages.publish')}}</button>
	     <a href="/" type="button" class="btn btn-warning resetQuestion" style="display: none;padding:5px;" data-id="0">{{trans('messages.reset')}}</a>	
            
             <br><br>   
            <video id="gum" controls muted style="border: 1px dotted white;" data-id="0"></video>
            <video id="questVideo" style="display:none" src="" data-id="0"></video>  
            </section>

         </div>    
  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" style="margin-top: 20px" data-dismiss="modal">{{trans('messages.close')}}</button>
      </div>
    </div>
  </div>
</div>
