<div class="panel">
    <h3 class="panel-heading">
        {l s='Reviewed'  d='Modules.Diliosrobotspro.Admin'}  - 
        <span>{l s='BY'  d='Modules.Diliosrobotspro.Admin'}<span> 
        <b>{$employee->firstname} {$employee->lastname}</b> -
        <span>{l s='AT'  d='Modules.Diliosrobotspro.Admin'}<span>
        <b>{$obj->date_add}</b>
    </h3>
    <div class="">
        <p>{$obj->revision nofilter}</p>
    </div>
</div>