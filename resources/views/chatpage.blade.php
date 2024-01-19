<?php
use Carbon\Carbon;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$serverData->name}}</title>
</head>
<body>
    <div style="display:grid; grid-template-columns:repeat(6, 1fr);">
        <div>
            <div style="width: 600px; height: 800px; background-color: lightblue; display: flex; flex-direction: column; overflow-y:scroll; overflow-x:hidden">
                <?php
                    $lastDate = Carbon::parse('2000-1-1');//initialize an old date so the first date comparisson is always true
                ?>
                @if (count($serverData->messages) != 0)
                    @foreach ($serverData->messages as $message)
                        <?php
                            $datetimeAssoc = explode(" ", $message->created_at);//we explode the string so we can sepparate the date from the time
                            $alignmentClass = $message->user_id == auth()->id() ? 'flex-start' : 'flex-end';
                        ?>
                        <form method="POST">
                            @csrf
                            @if (Carbon::parse($datetimeAssoc[0])->gt($lastDate))<!--checks if this message has a different date then the previous message-->
                                <?php
                                    $lastDate = $datetimeAssoc[0];
                                ?>
                                <div style="display: flex; justify-content:center; background-color: white; padding: 2px; margin: 4px; border-radius: 6px;">@if (Carbon::parse($datetimeAssoc[0])->isToday()) {{'Today'}} @else {{$datetimeAssoc[0]}} @endif</div>
                            @endif
                            <div style="display: flex; justify-content:<?php echo $alignmentClass?>;">
                                <div style="background-color: white; padding: 2px; margin: 4px; border-radius: 6px;">
                                    <p> @if (in_array($message->user_id, array_map(fn($item)=>$item->id,$serverData->users->all())))<!--checks if the owner of the message is still a user in this server-->
                                            {{$message->user->username}}
                                        @else
                                            {{'Removed-User'}}
                                        @endif 
                                        {{' at '.$datetimeAssoc[1]}}
                                        @if ($message->edited)
                                            {{' Edited at: '.$message->updated_at}}
                                        @endif
                                        @if ($message->user_id == auth()->id() || $serverData->user_id == auth()->id())<!--checks if the user is the owner of the message or the owner of the server-->
                                            <input hidden name="messageid" value="{{$message->id}}"> 
                                            <input type="submit" value="Delete" formaction="{{route('deleteMessage')}}">
                                            <button type="button" class="btn btn-danger" onclick="return editMessage('{{$message->id}}')">Edit</button>
                                        @endif</p>
                                    <p> 
                                        <textarea disabled id="messagebox{{$message->id}}" cols="40" rows="" name="editmessage">{{$message->content}}</textarea>
                                        <input hidden id="savebutton{{$message->id}}" type="submit" value="Save" formaction="{{route('editMessage')}}">
                                    </p>
                                </div>
                            </div>
                        </form>
                    @endforeach
                @else
                    <span>{{"no messages"}}</span>
                @endif
            </div>
                <form action="{{route('sendMessage')}}" method="POST">
                    @csrf
                    <textarea name="newchat" placeholder="Enter your message here :)" autocomplete="off" required></textarea>
                    <input type="submit" value="Send">
                </form>
                {{session()->get("messageSendError") ?? ""}} 
                @if ($serverData->user_id == auth()->id())
                    <form action="{{route('inviteUser')}}" method="POST">
                        @csrf
                        <input type="text" name="username" placeholder="Username" required>
                        <input type="submit" value="Invite new user">
                    </form>
                    {{session()->get("addUserError") ?? ""}} 
                
                    <form action="{{route('deleteServer')}}" method="POST" id="deleteServerForm">
                        @csrf
                        <input hidden name="confirmation" value="">
                        <button type="submit" class="btn btn-danger" onclick="return confirmDelete()">Delete Server</button>
                    </form>
                    {{session()->get("serverDeleteError") ?? ""}} 
                @endif

                <form action="{{route('overview')}}" method="GET">
                    <input type="submit" value="Back">
                </form>
        </div>
        <div style="width:200px; background-color:grey; color:white; overflow-y:scroll; overflow-x:hidden">
            Users:
            <form action="{{route('removeUser')}}" method="POST">
                @csrf
                @foreach ($serverData->users as $user)
                    <p>{{$user->username}} @if ($user->id == $serverData->user_id) (Owner👑) @elseif ($serverData->user_id == auth()->id()) <input hidden name="userid" value="{{$user->id}}"> <input type="submit" value="Remove"> @endif</p>
                @endforeach
            </form>
        </div>
    </div>
</body>
</html>

<script>
    //zodat je niet perongeluk je server verwijderd
    function confirmDelete() {
        var userConfirmation = confirm('You are about to delete this server and all its messages, are you sure?');
        document.getElementById('deleteServerForm').confirmation.value = userConfirmation ? "yes" : "no";
        return userConfirmation;
    }

    //maakt de berichten editable en geeft je een save knop
    function editMessage(id){
        var mb = document.getElementById("messagebox"+id);
        var sb = document.getElementById("savebutton"+id);
        mb.disabled = !mb.disabled;
        sb.hidden = !sb.hidden;
        return;
    }

    //dit hele stuk is om de textarea automatisch te laten groeien met de input
    const tx = document.getElementsByTagName("textarea");
    for (let i = 0; i < tx.length; i++) {
    tx[i].setAttribute("style", "height:" + (tx[i].scrollHeight) + "px;overflow-y:hidden;");
    tx[i].addEventListener("input", OnInput, false);
    }
    function OnInput() {
    this.style.height = 0;
    this.style.height = (this.scrollHeight) + "px";
    }
</script>