<section>
	<h3>Mes coordonnées</h3>
	<div class="cols">
		<article>
			<img class="img-round" src="img/linck.webp" alt="Sébastien Linck" title="Sébastien Linck">
		</article>
		
		<article>
			<h4>Sébastien Linck</h4>
			<br>
			École d’ingénieurs en Sciences Industrielles et Numérique - EiSINe<br>
			Campus Sup Ardenne<br>
			9A rue Claude Chrétien<br>
			08000 Charleville-Mézières<br><br>
			contact(@)slinck(.)com
			<br><br>
			<a target="_blank" href="https://www.linkedin.com/in/slinck/"><img class="social-icons" src="img/linkedin.svg" alt="Profil LinkedIn" title="Profil LinkedIn"></a>
			<a target="_blank" href="https://www.researchgate.net/profile/Sebastien-Linck"><img class="social-icons" src="img/researchgate.svg" alt="Profil ResearchGate" title="Profil ResearchGate"></a>
		</article>
		<article>
			<?php
				if (isset($_SESSION['success']) and $_SESSION['success'] == 1) {
					echo "Votre message a bien été transmis !";
				}
			?>
			<form id="contact-form" action="pages/send_form.php" method="post">
				<input required type="text" aria-label="name" name="nameslinck" id="nameslinck" placeholder="Votre nom">
				<input required type="email" aria-label="emailslinck" name="emailslinck" id="emailslinck" placeholder="Votre email">
				<textarea rows="6" required aria-label="message" id="messageslinck" name="messageslinck" placeholder="Votre message"></textarea>
				<label class="honeypot" for="name"></label>
				<input class="honeypot" autocomplete="off" type="text" id="name" name="name" placeholder="Your name here">
				<label class="honeypot" for="email"></label>
				<input class="honeypot" autocomplete="off" type="email" id="email" name="email" placeholder="Your e-mail here">
				<label class="honeypot" for="message"></label>
				<textarea class="honeypot" autocomplete="off" id="message" name="message"></textarea>
				<button type='submit'>Envoyer</button>
			</form>
		</article>
	</div>
</section>