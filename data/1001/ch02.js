var list = "To express feelings is good for your mental health./\
Starting the day actively with morning exercise is the key to losing weight./\
To know is one thing, to teach is another./\
To love and to be loved is the greatest happiness in this world./\
To call a person a pig is a serious insult in almost every language./\
Cramming for an important exam is never a good idea./\
Not getting enough sleep is a common cause of dark circles under the eyes./\
Trying to see the other person’s point of view is a good rule in life./\
Feeling gratitudes and not expressing it is like wrapping a present and not giving it./\
That she likes you is certain./\
Whether you will succeed or not in the future depends on your will./\
What was once high-tech is now old tech./\
That his novel will be made into a film is incredible./\
Whether you are rich or poor will not affect our friendship at all./\
What can’t be cured must be endured./\
Waht the politician says is different from what he does./\
Who will be our next door neighbers is my mom’s biggest concern./\
How the universe began cannot be explained clearly./\
It gives me a headache to think of the exam./\
It is true that health is above wealth./\
It is a good rule in life to use time effectively./\
It’s better to light a candle than to curse the darkness./\
It’s not easy being a parent./\
It is no use crying over spilt milk./\
It is clear that honesty and trust are vital to a relationship./\
It’s a wonder that nobody was hurt in the car accident./\
It is not certain whether there are aliens out there./\
It is a mystery why we fall in love./\
It is necessary for us to recycle waste./\
It was careless of you to lose your umbrella again./\
It is often difficult for a child to share his or her toys with others./\
It is natural for a boy of his age to be interested in girls./\
It is impossible for us to make progress without practicing every day./\
It is necessary for a new user of this website to first complete a registration form to gain access./\
It was rude of you to go away without saying goodbye./\
It is very considerate of you not to want to trouble others./\
It will take you twenty minutes to get to the station./\
It seems that he know the secret./\
It has been nearly three weeks since I last updated my blog./\
It’s getting colder and colder./\
It didn’t seem that she was lying./\
It appears to me that Susan has lost her temper./\
To my surprise, it happened that I got an A in math./\
Just as I was leaving the house, it occurred to me that I had forgotten my cellphone./\
The day most exciting for kids in Christmas./\
The book which you are looking for is in the drawer./\
Most books worth reading once are worth reading twice./\
The bruise on the back of my leg has disappeared./\
Vision without action is but a daydream./\
The most important key to success is positive thinking./\
The last person to leave the room must turn off the light./\
The two hardest things to handle in life are failure and success./\
People living in town don’t know the pleasures of country life./\
Books dealing with success and happiness are popular these days./\
The woman dressed in white is my favorite actress./\
A book written by a famous writer is not always a good book./\
The information presented on this website is free from error./\
Those who are good at excuses are seldom good at anything else./\
Plants which are easy to grow are the best choices for house-plants./\
Little did I know that the movie would take so long./\
There is nothing new under the sun./\
Now comes the end of the week./\
Between tomorrow’s dream and yesterday’s regret is today’s opportuniry./\
Around the corner stands the tallest building in the city./\
Happy are they who are content with their life./\
Not a single mistake did I find in your composition./\
Only then could I understand what she meant."

var tmp = list.split("/");
var data = [];
var j = 0;
for (var i=0; i<tmp.length; i++) {
	if (tmp[i].trim() == "") continue;
	else {
		var n = i+1+53;
		var fn = (n<10) ? "000"+n : ((n<100) ? "00"+n : "0"+n);
				//시도여부,	시도회수,	정답여부,	테스트한시간,	북마크,		failname,		스크립트
		data[j] = {	try:0,	count:0,	correct:0,	timestamp:0,	mark:0,		fn:"02_"+fn,	script:tmp[i].trim() };
		j++;
	}
}
