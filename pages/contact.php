<section>
	<h3>Mes coordonnées</h3>
	<div class="cols">
		<article>
			<img class="img-round" src="img/linck.webp" alt="Sébastien Linck" title="Sébastien Linck">
		</article>
		<article>
			<h5>Sébastien Linck</h5>
			EiSINe - Campus Sup Ardenne<br>
			9A rue Claude Chrétien<br>
			08000 Charleville-Mézières<br><br>
			contact(@)slinck(.)com
			<br><br>
			<a target="_blank" href="https://www.linkedin.com/in/slinck/"><img class="social-icons" src="img/linkedin.svg" alt="Profil LinkedIn" title="Profil LinkedIn"></a>
			<a target="_blank" href="https://www.researchgate.net/profile/Sebastien-Linck"><img class="social-icons" src="img/researchgate.svg" alt="Profil ResearchGate" title="Profil ResearchGate"></a>
		</article>
		<article>
			<?php
			$cible = mt_rand(1, 20);
			?>
			<form id="contact-form" action="pages/send_form.php" method="post">
				<input type="text" name="nom" placeholder="Votre nom" required>
				<input type="email" name="mail" placeholder="Votre courriel" required>
				<textarea rows="5" name="message" placeholder="Votre message" required></textarea>
				<label for="bip1" id="securite">Sécurité : Placer le curseur sur <?= $cible ?><span> Valeur actuelle : <span id="bip2">0</span></span></label>
				<input type="range" id="bip1" name="bip1" min="0" max="20" value="0" oninput="document.getElementById('bip2').textContent=this.value;" onchange="z=document.getElementById('envoyer-contact');if(this.value==<?= $cible ?>){z.disabled=false;}else{z.disabled=true;}">
				<input type="submit" name="envoyer" value="Envoyer" id="envoyer-contact" disabled onclick="if(document.getElementById('bip1').value!=<?= $cible ?>){return false;}">
				<input type="hidden" name="tps" value="<?= base_convert(($cible * 3) + date('z'), 10, 4) ?>">
			</form>
			<?php
			if (isset($_REQUEST['envoyer'], $_REQUEST['tps']) && is_numeric($_REQUEST['bip1'])) {
				$tps = (base_convert($_REQUEST['tps'], 4, 10) - date('z')) / 3;

				if ($tps == $_REQUEST['bip1']) {
					$to = 'contact@slinck.com';
					$from = strip_tags($_REQUEST['mail']);
					$nom = strip_tags($_REQUEST['nom']);
					$message = strip_tags($_REQUEST['message']);
					$subject = 'Message envoyé par ' . $nom;
					$subject = trim(iconv_mime_encode('', $subject, ['input-charset' => 'UTF-8', 'output-charset' => 'UTF-8']), ' :');
					$headers = [
						'MIME-Version: 1.0',
						'Content-type: text/html; charset=utf-8',
						'From: ' . $from,
					];
					$message = '<html><body><h3>Contenu du message:</h3><p>' . $message . '</p></body></html>';
					mail($to, $subject, $message, implode("\r\n", $headers));
					echo '<p>Message envoyé</p>';
				}
			}
			?>
		</article>
	</div>
</section>