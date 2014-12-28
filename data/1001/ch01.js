var list = "time flies like an arrow./\
Our school stands on the hill./\
Once there lived a man named Robin Hood./\
Our world is changing rapidly./\
A greate challenge lies ahead for our team./\
Age dosen’t marrer in love./\
These polls will work for you./\
Honesty will pay in the end./\
Every vote counts in an election./\
There is no royal road to learning./\
Your success grows from your struggles in life./\
Honesty is the best policy./\
This ring looks too tight for your finger./\
Today is the first day of the rest of your life./\
Nothing remains unchanged forever./\
Romor do not always prove to be true./\
At the touch of love, every one becomes a poet./\
My dream came true after all./\
Good medicine tastes bitter./\
Their complaints sound reasonable to me./\
The genius died young./\
A bad workman blames his tools./\
He put his hands in his pockets./\
We know that success is usually measured by results./\
According to an old saying, great hopes make great men./\
What kind of food would you like to have for lunch?/\
I want to hold your hand./\
I can’t stop loving you./\
Your new hair style suits you well./\
After two hours, we finally reached the coast./\
I don’t think that the ends can justify the means./\
Can you imagine what the world will be like without oil?/\
Children give their parents both headaches and pleasures./\
Bring me the bill, please./\
He left his wife a large fortune./\
I bought my girlfriend a necklace for her birthday./\
Can I ask you a faver?/\
I wish you a merry Christmas and a happy New Year./\
Online education can save you a lot of time and money./\
Poor communication can cost you your relationships or reputation./\
Bill promised his teacher not to cheat on an exam again./\
Could you tell me where the subway station is?/\
All work and no play makes Jack a dull boy./\
Did you find the math exam difficult?/\
Don’t call me a fool./\
Love makes the world a better place to live in./\
You drive me crazy./\
The noise from the outside kept me awake all night./\
Do you want me to take you to the station?/\
Let bygones be bygones./\
I have never heard anyone play a guitar like you./\
She kept me waiting for a long time./\
I got my arm broken yesterday.";

var tmp = list.split("/");
var data = [];
var j = 0;
for (var i=0; i<tmp.length; i++) {
	if (tmp[i].trim() == "") continue;
	else {
		var n = i+1;
		var fn = (n<10) ? "000"+n : ((n<100) ? "00"+n : "0"+n);
				//시도여부,	시도회수,	정답여부,	테스트한시간,	북마크,		failname,		스크립트
		data[j] = {	try:0,	count:0,	correct:0,	timestamp:0,	mark:0,		fn:"01_"+fn, 	script:tmp[i].trim() };
		j++;
	}
}
