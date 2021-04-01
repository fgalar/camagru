<div class="photobooth">
	<div class="main" >
		<div id="montageView">
			<video id="video" autoplay></video>
			<!--  Here we put the user montage preview-->
			<canvas id="imgPreview" width="500" height="375" ></canvas>

			<!--  Upload photo method  -->
			<div id="uploadPhoto">
				<form id='upload' action='photobooth/share' formmethod='post' enctype='multipart/form-data'>
					<input type='file' id='uploader' accept="image/png, image/jpeg" />
				</form>
			</div>

			<div id="snapPhoto">
				<button id='snapBtn' class='snap' disabled onclick="takePhoto()">Snap</button>
				<audio id="snapNoise" class='snap' src="https://freesound.org/data/previews/202/202296_1038806-lq.mp3" hidden></audio>
			</div>

		</div><!--  end montage  -->

		<div id="boxFilter">
			<img class="filter" onclick="selectFilter('BG_filter')" id='BG_filter'src="./tools/img/filterBG.png" alt="filter">
			<img class="filter" onclick="selectFilter('coco_filter')" id='coco_filter'src="./tools/img/filter2020.png" alt="filter">
			<img class="filter" onclick="selectFilter('strabisme_filter')" id='strabisme_filter'src="./tools/img/filterStrabisme.png" alt="filter">
			<img class="filter" onclick="selectFilter('steve_filter')" id='steve_filter'src="./tools/img/filterSteve.png" alt="filter">
			<img class="filter" onclick="selectFilter('donald_filter')" id='donald_filter'src="./tools/img/filterFired.png" alt="filter">
			<img class="filter" onclick="selectFilter('nasdrovia_filter')" id='nasdrovia_filter'src="./tools/img/filterNasdrovia.png" alt="filter">
		</div> <!--  end boxFilter  -->

	</div><!--  end main  -->
	<div id="side" class="photoBox">

	</div> <!--  end side  -->
</div> <!--  end photobooth -->

