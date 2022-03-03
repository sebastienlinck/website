<section>
	<h3>Mes coordonnées</h3>
	<div id="cols3">
		<article>
			<img class="img-round" src="img/linck.webp" alt="Sébastien Linck" title="Sébastien Linck">
		</article>

		<article>
			<h4>Sébastien Linck</h4>
			<br>
			École d’ingénieurs en Sciences Industrielles et Numérique - EiSINe<br>
			Campus Sup Ardenne - 9A rue Claude Chrétien<br>
			08000 - Charleville-Mézières<br><br>
			contact(@)slinck(.)com
			<br><br>
			<a target="_blank" href="https://fr.linkedin.com/in/slinck/"><img class="social-icons" src="img/linkedin.png" alt="Profil LinkedIn" title="Profil LinkedIn"></a>
			<a target="_blank" href="https://www.researchgate.net/profile/Sebastien_Linck"><img class="social-icons" src="img/researchgate.png" alt="Profil ResearchGate" title="Profil ResearchGate"></a>
		</article>

		<article>
			<?php
			if (isset($_SESSION['success']) and $_SESSION['success'] == 1) {
				echo "Votre message a bien été transmis !";
			}
			if (isset($_SESSION['success']) and $_SESSION['success'] == 0) {
				echo "Veuillez remplir le captcha !";
			}
			?>
			<form id="contact-form" action="pages/send_form.php" method="post">
				<input required type="text" name="name" id="name" value="<?php echo isset($_SESSION['inputs']['name']) ? $_SESSION['inputs']['name'] : ''; ?>" placeholder="Votre nom">
				<input required type="email" name="email" id="email" value="<?php echo isset($_SESSION['inputs']['email']) ? $_SESSION['inputs']['email'] : ''; ?>" placeholder="Votre email">
				<textarea rows="4" required id="message" name="message" placeholder="Votre message"><?php echo isset($_SESSION['inputs']['message']) ? $_SESSION['inputs']['message'] : ''; ?></textarea>
				<button class="g-recaptcha" data-sitekey="6LcBwRgUAAAAADQmcYmD0Tr0LTMM78W0k3Qmabl7" data-callback='onSubmit'>Envoyer</button>
			</form>
		</article>
	</div>
</section>