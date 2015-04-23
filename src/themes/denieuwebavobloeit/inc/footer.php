<?php
/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 3/21/2015
 * Time: 2:05 PM
 */
$year = date('Y');
?>
<footer class="home-footer">
    <div class="home-site-info">
        &copy; <?php echo($year == 2015 ? $year : '2015 - ' . $year); ?> <a href="http://www.rkbavo.nl" target="_blank">Kathedrale
            Basiliek Sint Bavo</a>
    </div>
</footer>

</div>
<?php
wp_footer();
?>
</body>
</html>
