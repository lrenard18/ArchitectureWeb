<?php
	if (!isset($_GET['page']) || $_GET['page']==NULL) { //Si la variable 'page' n'existe pas (dans l'URL), alors on redirige afin qu'elle existe.
		header("Location: commentaires.php?page=1");
	}
?>

<!doctype html> 
<html>
	<head> 
		<meta charset="utf-8">
	</head> 
	<body>
		<form method="POST">
			<input type="text" name="nom" placeholder="Nom" required><br>
			<textarea name="comment" placeholder="Commentaire" required></textarea><br>
			<input type="submit" value="Envoyer" name="envoyer">
		</form>
		<?php
			$page=$_GET['page'];
			$lien=mysqli_connect("localhost","root","","tp");
			if(isset($_POST['envoyer']))
			{
				$nom=trim(htmlentities(mysqli_real_escape_string($lien,$_POST['nom'])));
				$comment=trim(htmlentities(mysqli_real_escape_string($lien,$_POST['comment'])));
				$req="INSERT INTO comment VALUES (NULL,'$nom','$comment')";
				$res=mysqli_query($lien,$req);
				if(!$res)
				{
					echo "Erreur SQL:$req<br>".mysqli_error($lien);
				}
			}
			
			
			$commparpage=2;
			$premiercomm=$commparpage*($page-1);
			$req="SELECT * FROM comment ORDER BY id LIMIT $premiercomm,$commparpage";/* LIMIT dit ou je commence et combien j'en prends*/
			$res=mysqli_query($lien,$req);
			if(!$res)
			{
				echo "erreur SQL:$req<br>".mysqli_error($lien);
			}
			else
			{
				while($tableau=mysqli_fetch_array($res))
				{
					echo "<h2>".$tableau['nom']."</h2>";
					echo "<p>".$tableau['contenu']."</p>";
				}
			}
			
			$req="SELECT * FROM comment";
			$res=mysqli_query($lien,$req);
			if(!$res)
			{
				echo "Erreur SQL:$req<br>".mysqli_error($lien);
			}
			else
			{
				$nbcomm=mysqli_num_rows($res); /* Retourne le nombre de lignes dans un résultat. */
				$nbpages=ceil($nbcomm/$commparpage); /*Ceil arrondit a l'entier supérieur*/
				echo "<br> Pages : ";
				
				if (($page-1) > 0) { //Affiche les flèches que si le retour à la page précédente est possible (donc pas depuis la page 1 vu que la page 0 n'existe pas)
					echo "<a href='commentaires.php?page=1'> << </a>";
					echo "<a href='commentaires.php?page=" . ($page-1) . "'> < </a>";
				}
				$depPage=0;
				if ($page < 3) {
					$depPage=3-$page;
				}
				if ($page > ($nbpages-2)) {
					$depPage=($nbpages-2)-$page;
				}
				if ($nbpages < 5) {
					for($i=1;$i<=$nbpages;$i++) {
						if ($i == $page) {
							echo "<b> $i </b>";
						} else {
							echo "<a href='commentaires.php?page=$i'> $i </a>";
						}
					}
				} else {
					for($i=($page-2+$depPage);$i<=($page+2+$depPage);$i++) {
						if ($i == $page) {
							echo "<b> $i </b>";
						} else {
							echo "<a href='commentaires.php?page=$i'> $i </a>";
						}
					}
				}
				
				
				if (($page+1) <= $nbpages) { //Affiche les flèches que si l'aller à la page psuivante est possible (donc pas depuis la dernière page vu que la page suivante n'existe pas)
					echo "<a href='commentaires.php?page=" . ($page+1) . "'> > </a>";
					echo "<a href='commentaires.php?page=$nbpages'> >> </a>";
				}
			}
			
			mysqli_close($lien);
		?>																			