<?php
include 'db_connect.php';
$qry = $conn->query("SELECT * FROM parcels where id = ".$_GET['id'])->fetch_array();
foreach($qry as $k => $v){
	$$k = $v;
}
if($to_branch_id > 0 || $from_branch_id > 0){
	$to_branch_id = $to_branch_id  > 0 ? $to_branch_id  : '-1';
	$from_branch_id = $from_branch_id  > 0 ? $from_branch_id  : '-1';
$branch = array();
 $branches = $conn->query("SELECT *,concat(street,', ',city,', ',state,', ',zip_code,', ',country) as address FROM branches where id in ($to_branch_id,$from_branch_id)");
    while($row = $branches->fetch_assoc()):
    	$branch[$row['id']] = $row['address'];
	endwhile;
}
?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-md-12">
				<div class="callout callout-info">
					<dl>
						<dt>Takip Numarası:</dt>
						<dd> <h4><b><?php echo $reference_number ?></b></h4></dd>
					</dl>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="callout callout-info">
					<b class="border-bottom border-primary">Gönderen Bilgisi</b>
					<dl>
						<dt>İsim:</dt>
						<dd><?php echo ucwords($sender_name) ?></dd>
						<dt>Adres:</dt>
						<dd><?php echo ucwords($sender_address) ?></dd>
						<dt>İletişim:</dt>
						<dd><?php echo ucwords($sender_contact) ?></dd>
					</dl>
				</div>
				<div class="callout callout-info">
					<b class="border-bottom border-primary">Alıcı Bilgisi</b>
					<dl>
						<dt>İsim:</dt>
						<dd><?php echo ucwords($recipient_name) ?></dd>
						<dt>Adres:</dt>
						<dd><?php echo ucwords($recipient_address) ?></dd>
						<dt>İletişim:</dt>
						<dd><?php echo ucwords($recipient_contact) ?></dd>
					</dl>
				</div>
			</div>
			<div class="col-md-6">
				<div class="callout callout-info">
					<b class="border-bottom border-primary">İşlem Detayları</b>
						<div class="row">
							<div class="col-sm-6">
								<dl>
									<dt>Ağırlık:</dt>
									<dd><?php echo $weight ?></dd>
									<dt>Yükseklik:</dt>
									<dd><?php echo $height ?></dd>
									<dt>Fiyat:</dt>
									<dd><?php echo number_format($price,2) ?></dd>
								</dl>	
							</div>
							<div class="col-sm-6">
								<dl>
									<dt>Genişlik:</dt>
									<dd><?php echo $width ?></dd>
									<dt>Uzunluk:</dt>
									<dd><?php echo $length ?></dd>
									<dt>Type:</dt>
									<dd><?php echo $type == 1 ? "<span class='badge badge-primary'>Deliver to Recipient</span>":"<span class='badge badge-info'>Pickup</span>" ?></dd>
								</dl>	
							</div>
						</div>
					<dl>
						<dt>Gönderici Şube:</dt>
						<dd><?php echo ucwords($branch[$from_branch_id]) ?></dd>
						<?php if($type == 2): ?>
							<dt>Teslimat İçin En Yakın Şube:</dt>
							<dd><?php echo ucwords($branch[$to_branch_id]) ?></dd>
						<?php endif; ?>
						<dt>Durum:</dt>
						<dd>
							<?php 
							switch ($status) {
								case '1':
									echo "<span class='badge badge-pill badge-info'> Kargo Toplandı</span>";
									break;
								case '2':
									echo "<span class='badge badge-pill badge-info'> Sevk Edildi</span>";
									break;
								case '3':
									echo "<span class='badge badge-pill badge-primary'> Sevk Edilmekte</span>";
									break;
								case '4':
									echo "<span class='badge badge-pill badge-primary'> Hedefe Ulaşıldı</span>";
									break;
								case '5':
									echo "<span class='badge badge-pill badge-primary'> Dağıtımda</span>";
									break;
								case '6':
									echo "<span class='badge badge-pill badge-primary'> Teslimata Hazır</span>";
									break;
								case '7':
									echo "<span class='badge badge-pill badge-success'>Gönderildi</span>";
									break;
								case '8':
									echo "<span class='badge badge-pill badge-success'> Teslim Alındı</span>";
									break;
								case '9':
									echo "<span class='badge badge-pill badge-danger'> Başarısız Kargo İşlemi</span>";
									break;
								
								default:
									echo "<span class='badge badge-pill badge-info'> Kargo Kurye Tarafından Teslim Alındı</span>";
									
									break;
							}

							?>
							<span class="btn badge badge-primary bg-gradient-primary" id='update_status'><i class="fa fa-edit"></i> Durum Güncelle</span>
						</dd>

					</dl>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer display p-0 m-0">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
</div>
<style>
	#uni_modal .modal-footer{
		display: none
	}
	#uni_modal .modal-footer.display{
		display: flex
	}
</style>
<noscript>
	<style>
		table.table{
			width:100%;
			border-collapse: collapse;
		}
		table.table tr,table.table th, table.table td{
			border:1px solid;
		}
		.text-cnter{
			text-align: center;
		}
	</style>
	<h3 class="text-center"><b>Student Result</b></h3>
</noscript>
<script>
	$('#update_status').click(function(){
		uni_modal("Update Status of: <?php echo $reference_number ?>","manage_parcel_status.php?id=<?php echo $id ?>&cs=<?php echo $status ?>","")
	})
</script>