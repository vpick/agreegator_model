    <script>
        $(document).ready(function () {
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            const hasAnyParameter = urlParams.keys().next().done === false;
            // Set the display property based on whether any parameter is present
            $('#filter').css('display', hasAnyParameter ? 'block' : 'none');
        });
    </script>
    <footer class="main-footer w-100 position-absolute bottom-0 start-0 py-2" style="background: #222">
        <div class="container-fluid">
          <div class="row text-center gy-3">
            <div class="col-sm-6 text-sm-start">
              <p class="mb-0 text-sm text-gray-600">Omneelab &copy; 2017-2022</p>
            </div>
            <div class="col-sm-6 text-sm-end">
              <p class="mb-0 text-sm text-gray-600">Powered by <a href="#" class="external">Omneelab</a></p>
              <!-- 
			  https://bootstrapious.com/p/bootstrap-4-dashboard
			  Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions and it helps me to run Bootstrapious. Thank you for understanding :)-->
            </div>
          </div>
        </div>
      </footer>
    </div>
<!-- JavaScript files-->
    <script src="{{ url('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ url('vendor/just-validate/js/just-validate.min.js') }}"></script>
    <!--<script src="{{ url('vendor/choices.js/public/assets/scripts/choices.min.js') }}"></script>-->
    <script src="{{ url('vendor/overlayscrollbars/js/OverlayScrollbars.min.js') }}"></script>
    <!--<script src="{{ url('js/charts-home.js') }}"></script>-->
     <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.js"></script>
     <script src="{{ url('js/multiple-select.min.js') }}"></script>
    <!-- Main File-->
    <script src="{{ url('js/front.js') }}"></script>
    <script>

      // ------------------------------------------------------- //
      //   Inject SVG Sprite - 
      //   see more here 
      //   https://css-tricks.com/ajaxing-svg-sprite/
      // ------------------------------------------------------ //
      function injectSvgSprite(path) {
      
          var ajax = new XMLHttpRequest();
          ajax.open("GET", path, true);
          ajax.send();
          ajax.onload = function(e) {
          var div = document.createElement("div");
          div.className = 'd-none';
          div.innerHTML = ajax.responseText;
          document.body.insertBefore(div, document.body.childNodes[0]);
          }
      }
      // this is set to BootstrapTemple website as you cannot 
      // inject local SVG sprite (using only 'icons/orion-svg-sprite.svg' path)
      // while using file:// protocol
      // pls don't forget to change to your domain :)
      injectSvgSprite('https://bootstraptemple.com/files/icons/orion-svg-sprite.svg'); 
    </script>
	<!--<script type="text/javascript">-->
 <!--   	var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();-->
 <!--   	(function(){-->
 <!--   	var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];-->
 <!--   	s1.async=true;-->
 <!--   	s1.src='https://embed.tawk.to/649c273f94cf5d49dc605229/1h40vil4r';-->
 <!--   	s1.charset='UTF-8';-->
 <!--   	s1.setAttribute('crossorigin','*');-->
 <!--   	s0.parentNode.insertBefore(s1,s0);-->
 <!--   	})();-->
	<!--</script>-->
    <!-- FontAwesome CSS - loading as last, so it doesn't block rendering-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <script type="text/javascript">
    	$(function() {
	        var range = $('#range').val();
	        if(typeof range !== 'undefined')
	        {
        		if(range!= '' ){
        			var sparate = range.split('-');
        			var start = sparate[0];
        			var end = sparate[1];
        		}
        		else{
        			var start = moment().subtract(29, 'days');
        			var end = moment();
        		}
    	}

    		function cb(start, end) {
    		   if(typeof range !== 'undefined')
    		   {
        			if(range != '')   {
        				$('#reportrange').val(range);
        				$('#reportrange span').html(range);
        			} 
        			else{
        				$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        			}	
        		}
            }
		$('#reportrange').daterangepicker({
			startDate: start,
			endDate: end,
			ranges: {
			   'Today': [moment(), moment()],
			   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			   'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			   'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			   'This Month': [moment().startOf('month'), moment().endOf('month')],
			   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			}
		}, cb);

		cb(start, end);
	});
	</script>
    <script>
	  function showFilter()
	  {
		$("#filter").toggle();  
	  }
	</script>
	
	<script>
    	let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    	elems.forEach(function(html) {
    		let switchery = new Switchery(html,  { size: 'small' });
    	});
	</script>
	<script>
		$(function() {
			$('.multiple-select').multipleSelect({
			placeholder: 'Select',
			minimumCountSelected: 5,
			showClear: true,
			filter: true,	 
			openOnHover: true,
			
			})
		})
	</script>
	<script>
			$('.refresh_orders_button').on('click',function(){
            	location.reload();
        	});
		</script>
	
    	 @if(session('status'))
    		<script type="text/javascript">
    	
    			Swal.fire({
    				title: 'Success!',
    				text: "{{ session('status') }}",
    				timer: 2000,
    				icon: 'success'
    			}).then(function() {
                    location.reload();
                    
                }) 
    			 
    		</script>
    	@endif
    	
		@if(session('warning'))
    		<script type="text/javascript">
    			Swal.fire({
    				title: 'warning!',
    				text: "{{ session('warning') }}",
    				timer: 2000,
    				icon: 'warning'
    			});
    		</script>
    	@endif
		@if(session('info'))
    		<script type="text/javascript">
    			Swal.fire({
    				title: 'info!',
    				text: "{{ session('info') }}",
    				timer: 2000,
    				icon: 'info'
    			});
    		</script>
    	@endif
    	@if(session('error'))
    		<script type="text/javascript">
    			Swal.fire({
    				title: 'error!',
    				text: "{{ session('error') }}",
    				timer: 2000,
    				icon: 'error'
    			});
    		</script>
    	@endif
    	@if($errors->any())
            <script type="text/javascript">
    			Swal.fire({
    				title: 'Failed!',
    				text: "{{ $errors->first() }}",
    				timer: 5000,
    				icon: 'failed'
    			});
    		
    		</script>
        @endif
        
        <script>
            function checkPermission()
            {
              swal.fire({
                title: "Info",
                text: "You do not have permission ",
                type: "warning",
              }).then(function() {
                    location.reload();
                    
                }) 
                
            }
        </script>

        