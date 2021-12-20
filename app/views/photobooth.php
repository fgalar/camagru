<div class="photobooth">
	<div class="main" >
		<div id="montageView">
			<video id="video" autoplay></video>
			<!--  Here we put the user montage preview-->
			<canvas id="imgPreview"></canvas>

			<!--  Upload photo method  -->
			<div id="uploadPhoto">
				<form id='upload' action='photobooth/share' formmethod='post' enctype='multipart/form-data'>
					<input type='file' id='uploader' accept="image/png, image/jpeg" />
				</form>
			</div>

			<div id="snapPhoto">
				<button id='snapBtn' class='snap' disabled onclick="takePhoto()">◻︎</button>
				<audio id="snapNoise" class='snap' src="https://freesound.org/data/previews/202/202296_1038806-lq.mp3" hidden></audio>
			</div>

		</div><!--  end montage  -->

		<div id="boxFilter">
			<img class="filter" onclick="selectFilter('fr_filter')" id='fr_filter'src="./tools/img/fltr_fr.png" alt="filter">
			<img class="filter" onclick="selectFilter('us_filter')" id='us_filter'src="./tools/img/fltr_us.png" alt="filter">
			<img class="filter" onclick="selectFilter('ru_filter')" id='ru_filter'src="./tools/img/fltr_ru.png" alt="filter">
			<img class="filter" onclick="selectFilter('mask_filter')" id='mask_filter'src="./tools/img/fltr_mask.png" alt="filter">
			<!-- <img class="filter" onclick="selectFilter('donald_filter')" id='donald_filter'src="./tools/img/filterFired.png" alt="filter">
			<img class="filter" onclick="selectFilter('nasdrovia_filter')" id='nasdrovia_filter'src="./tools/img/filterNasdrovia.png" alt="filter"> -->
		</div> <!--  end boxFilter  -->

	</div><!--  end main  -->
	<div id="side" class="photoBox">

	</div> <!--  end side  -->
</div> <!--  end photobooth -->

