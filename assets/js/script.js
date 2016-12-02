$(document).ready(function(){
    var highestBox = 0;
        $('.real-content').each(function(){  
                if($(this).height() > highestBox){  
                highestBox = $(this).height();  
        }
    });
    var FinalHighestBox = highestBox - 80;
    $('#content').height(FinalHighestBox);
    var highestBox = 0;
        $('.big-real-content').each(function(){  
                if($(this).height() > highestBox){  
                highestBox = $(this).height();  
        }
    });
    var FinalHighestBox = highestBox - 80;
    $('#big-content').height(FinalHighestBox);
    var highestBox = 0;
        $('.indirect-real-content').each(function(){  
                if($(this).height() > highestBox){  
                highestBox = $(this).height();  
        }
    });
    var FinalHighestBox = highestBox - 80;
    $('#indirect-content').height(FinalHighestBox);
});

function UpdatePrice(obj){
    document.getElementById('total-price').innerHTML = obj.value ? obj.value : '0';
}