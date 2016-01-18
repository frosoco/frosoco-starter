<div class="page-header">
    <h1>Events</h1>
</div>

<? if ($this->session->userdata('id')) { ?>

<div class="calendar">
    <iframe src="https://www.google.com/calendar/embed?title=FroSoCo%20Events&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=vpl5dg411sv3stp4utvlsc7ddo%40group.calendar.google.com&amp;color=%23B1440E&amp;src=seuvs820ra1piu8mou4mqgcomg%40group.calendar.google.com&amp;color=%235229A3&amp;ctz=America%2FLos_Angeles" style=" border-width:0 " width="800" height="600" frameborder="0" scrolling="no"></iframe>
</div>

<? } else { ?>

<div class="calendar">
    <p>Login to view private FroSoCo events.</p>
    <iframe src="https://www.google.com/calendar/embed?title=FroSoCo%20Events&amp;height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=seuvs820ra1piu8mou4mqgcomg%40group.calendar.google.com&amp;color=%235229A3&amp;ctz=America%2FLos_Angeles" style=" border-width:0 " width="800" height="600" frameborder="0" scrolling="no"></iframe>
</div>

<? } ?>
