<template>
	<div class="row">
		<div class="col-md-3">
			<div class="card text-white bg-primary">
				<div class="card-body pb-0">
					<div class="btn-group float-right">
						<button type="button" class="btn btn-transparent dropdown-toggle p-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="icon-settings"></i>
						</button>
						<div class="dropdown-menu dropdown-menu-right">
							<a class="dropdown-item" href="#">Action</a>
							<a class="dropdown-item" href="#">Another action</a>
							<a class="dropdown-item" href="#">Something else here</a>
						</div>
					</div>
					<div class="text-value">9.823</div>
					<div>Members online</div>
				</div>
				<div class="chart-wrapper mt-3 mx-3" style="height:70px;"><div class="chartjs-size-monitor" style="position: absolute; left: 0px; top: 0px; right: 0px; bottom: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
				<canvas id="card-chart1" class="chart chartjs-render-monitor" height="70" style="display: block; width: 199px; height: 70px;" width="199"></canvas>
				<div id="card-chart1-tooltip" class="chartjs-tooltip top" style="opacity: 0; left: 116.538px; top: 93.2778px;"><div class="tooltip-header"><div class="tooltip-header-item">April</div></div><div class="tooltip-body"><div class="tooltip-body-item"><span class="tooltip-body-item-color" style="background-color: rgb(0, 165, 224);"></span><span class="tooltip-body-item-label">My First dataset</span><span class="tooltip-body-item-value">84</span></div></div></div></div>
			</div>
		</div>
	</div>
</template>

<script>
	export default  {
		head: {
			title: {
				inner: 'Home',
				separator: ' ',
			}
		},

		mounted () {
			
		}
	}
</script>