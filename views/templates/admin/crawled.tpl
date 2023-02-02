<div class="panel">
    <h3 class="panel-heading">
        {l s='Crawled'  d='Modules.Diliosrobotspro.Admin'}
    </h3>
    <div class="">
        <p>
            <b>{l s='User Agent'  d='Modules.Diliosrobotspro.Admin'}</b>
            <br>
            {$obj->user_agent nofilter}
        <p>
        <p>
            <b>{l s='Boot Name'  d='Modules.Diliosrobotspro.Admin'}</b>
            <br>
            {$obj->boot_name nofilter}
        <p>
        <p>
            <b>{l s='Crawls'  d='Modules.Diliosrobotspro.Admin'}</b>
            <br>
            {$obj->crawls nofilter}
        <p>
        <p>
            <b>{l s='Date'  d='Modules.Diliosrobotspro.Admin'}</b>
            <br>
            {$obj->date_add}
        <p>
    </div>
</div>