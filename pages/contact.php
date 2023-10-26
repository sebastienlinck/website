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
				<label for="nameslinck">Nom</label>
				<input required type="text" aria-label="name" name="nameslinck" id="nameslinck" required>
				<label style="display:none;">Ne pas remplir :</label>
				<input type="text" name="honeypot" id="honeypot">
				<label for="emailslinck">Courriel</label>
				<input required type="email" aria-label="email" name="emailslinck" id="emailslinck" required>
				<label for="messageslinck">Message</label>
				<textarea rows="6" required aria-label="message" id="messageslinck" name="messageslinck" required></textarea>
				<button type='submit'>Envoyer</button>
			</form>
		</article>
	</div>
</section>