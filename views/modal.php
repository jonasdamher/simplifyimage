<div class="modal">
	<div class="card sw-sm">
		<div class="card-body">
			<div class="d-flex-column">

				<?php if($_GET['valid'] == 1 ) { ?>
					
				<p class="mb-2">Upload image successful.</p>
				<a title="See image" target="__blank" class="link" href="http://localhost/image/public/images/users/<?= $upload["filename"] ?>">
					See image
				</a>

				<?php } else { 
					foreach($upload['errors'] as $error) { ?>
				
					<p><?= $error ?></p>
				
				<?php } } ?>
				
				<div class="mt-2 d-flex j-center">
					<a class="btn btn-orange sw-md text-bold" href="uploadOneImage.php">Ok</a>
				</div>
			</div>
		</div>
	</div>
</div>
