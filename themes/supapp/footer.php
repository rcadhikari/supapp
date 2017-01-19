		
    	   </div>
        </div>
            
        <footer>
            <div id="inner-footer" class="vertical-nav">
                <div class="container">
                    <div class="row">
                        <?php dynamic_sidebar('footer1'); ?>

                        <div class="col-xs-12 text-center">
                            <p>Developed by <a href="<?php echo esc_url( __( 'https://github.com/rcadhikari', 'RC Adhikari') ); ?>"><?php printf( __( '%s', 'wp-basic-bootstrap' ), 'RC Adhikari' ); ?></a></p>
                            <p>Powered by <a href="<?php echo esc_url( __( 'http://www.wordpress.org', 'WordPress') ); ?>"><?php printf( __( '%s', 'wp-basic-bootstrap' ), 'Wordpress' ); ?></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    </div>

	<?php wp_footer(); ?>

</body>

</html>