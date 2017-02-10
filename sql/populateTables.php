<?php
//require '../sn/database.php'; //uncomment this if you need to call this individual script
$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$insertRolesTable = "INSERT INTO MyDB.roles (roleID,roleTitle) VALUES (1,\"administrator\"), (2,\"user\")";
$insertRightsTable = "INSERT INTO MyDB.rights (roleID,rightTitle,rightDescription) VALUES
(1, \"Delete User\", \"The user has the right to delete other user\"),
(1, \"Delete User's Photos\", \"The user has the right to delete other user's photos\"),
(1, \"Delete User's Photo Collection\", \"The user has the right to delete other user's photo collections\"),
(1, \"Delete User's Annotations\", \"The user has the right to delete other user's annotations\"),
(1, \"Delete User's Blog Posts\", \"The user has the right to delete other user's blog posts\"),
(1, \"Delete User's Blog\", \"The user has the right to delete other user's blog\"),
(1, \"Edit User's Profile Details\", \"The user has the right to edit other user's profile details\"),
(1, \"Edit User's Photo\", \"The user has the right to edit other user's photo\"),
(1, \"Edit User's Photo Collection\", \"The user has the right to edit other user's photo collection\"),
(1, \"Edit User's Annotations\", \"The user has the right to edit other user's annotations\"),
(1, \"Edit User's Blog Posts\", \"The user has the right to edit other user's blog posts\"),
(1, \"Edit User's Blog\", \"The user has the right to edit other user's blog\")";
$insertUsersTable = "INSERT INTO MyDB.users (email,roleID,user_password,firstName,lastName,profileImage) VALUES
(\"alan@ucl.ac.uk\",2,\"test\",\"Alan\",\"Turing\",\"/images/profile/alan@ucl.ac.uk.jpg\"),
(\"ada@ucl.ac.uk\",2,\"test\",\"Ada\",\"Lovelace\",\"/images/profile/ada@ucl.ac.uk.jpg\"),
(\"grace@ucl.ac.uk\",2,\"test\",\"Grace\",\"Hopper\",\"/images/profile/grace@ucl.ac.uk.jpg\"),
(\"john@ucl.ac.uk\",2,\"test\",\"John\",\"von Neumann\",\"/images/profile/john@ucl.ac.uk.jpg\"),
(\"tim@ucl.ac.uk\",2,\"test\",\"Tim\",\"Berners-Lee\",\"/images/profile/tim@ucl.ac.uk.jpg\"),
(\"dennis@ucl.ac.uk\",2,\"test\",\"Dennis\",\"Ritchie\",\"/images/profile/dennis@ucl.ac.uk.jpg\"),
(\"ken@ucl.ac.uk\",2,\"test\",\"Ken\",\"Thompson\",\"/images/profile/ken@ucl.ac.uk.jpg\"),
(\"larry@ucl.ac.uk\",2,\"test\",\"Larry\",\"Page\",\"/images/profile/larry@ucl.ac.uk.jpg\"),
(\"charles@ucl.ac.uk\",1,\"test\",\"Charles\",\"Babbage\",\"/images/profile/charles@ucl.ac.uk.jpg\"),
(\"vicky@ucl.ac.uk\",1,\"test\",\"Vicky\",\"LovesPHP\",\"/images/profile/vicky@ucl.ac.uk.jpg\")";
$insertFriendshipTable = "INSERT INTO MyDB.friendships (emailFrom,emailTo,status) VALUES
(\"charles@ucl.ac.uk\",\"larry@ucl.ac.uk\",\"accepted\"),
(\"charles@ucl.ac.uk\",\"ken@ucl.ac.uk\",\"accepted\"),
(\"charles@ucl.ac.uk\",\"ada@ucl.ac.uk\",\"accepted\"),
(\"charles@ucl.ac.uk\",\"alan@ucl.ac.uk\",\"pending\"),
(\"charles@ucl.ac.uk\",\"john@ucl.ac.uk\",\"denied\"),
(\"vicky@ucl.ac.uk\",\"charles@ucl.ac.uk\",\"accepted\"),
(\"grace@ucl.ac.uk\",\"ada@ucl.ac.uk\",\"accepted\"),
(\"grace@ucl.ac.uk\",\"alan@ucl.ac.uk\",\"accepted\"),
(\"grace@ucl.ac.uk\",\"john@ucl.ac.uk\",\"accepted\"),
(\"grace@ucl.ac.uk\",\"tim@ucl.ac.uk\",\"pending\"),
(\"grace@ucl.ac.uk\",\"denis@ucl.ac.uk\",\"accepted\"),
(\"grace@ucl.ac.uk\",\"ken@ucl.ac.uk\",\"accepted\")";
$insertBlogsTable = "INSERT INTO MyDB.blogs (blogId,email,blogTitle,blogDescription) VALUES
(1,\"charles@ucl.ac.uk\",\"Passeges from the Life of a Philosophe\",\"Perhaps it would be better for science, that all criticism should be avowed.\"),
(2,\"larry@ucl.ac.uk\",\"Changing the world\",\"You never lose a dream. It just incubates as a hobby.\"),
(3,\"ken@ucl.ac.uk\",\"Belle\",\"If you want to go somewhere, goto is the best way to get there.\")";
$insertPostsTable = "INSERT INTO MyDB.posts (blogId,postTitle,postText) VALUES
(1,\"Preface\", \"Some men write their lives to save themselves from ennui, careless of the amount they inflict on their readers.
Others write their personal history, lest some kind friend should survive them, and, in showing off his own talent, unwittingly show them up.
Others, again, write their own life from a different motive—from fear that the vampires of literature might make it their prey.
I have frequently had applications to write my life, both from my countrymen and from foreigners. Some caterers for the public offered to pay me for it. Others required that I should pay them for its insertion; others offered to insert it without charge. One proposed to give me a quarter of a column gratis, and as many additional lines of eloge as I chose to write and pay for at ten-pence per line. To many of these I sent a list of my works, with the remark that they formed the best life of an author; but nobody cared to insert them.
I have no desire to write my own biography, as long as I have strength and means to do better work.
The remarkable circumstances attending those Calculating Machines, on which I have spent so large a portion of my life, make me wish to place on record some account of their past history. As, however, such a work would be utterly uninteresting to the greater part of my countrymen, I thought it might be rendered less unpalatable by relating some of my experience amongst various classes of society, widely differing from each other, in which I have occasionally mixed.
This volume does not aspire to the name of an autobiography. It relates a variety of isolated circumstances in which I have taken part—some of them arranged in the order of time, and others grouped together in separate chapters, from similarity of subject.
The selection has been made in some cases from the importance of the matter. In others, from the celebrity of the persons concerned ; whilst several of them furnish interesting illustrations of human character.\"),
(1,\"My Ancestors\",\"What is there in a name? It is merely an empty basket, until you put something into it. My earliest visit to the Continent taught me the value of such a basket, filled with the name of my venerable friend the first Herschel, ere yet my younger friend his son, had adorned his distinguished patronymic with the additional laurels of his own well-earned fame.
The inheritance of a celebrated name is not, however, without its disadvantages. This truth I never found more fully appreciated, nor more admirably expressed, than in a conversation with the son of Filangieri, the author of the celebrated Treatise on Legislation, with whom I became acquainted at Naples, and in whose company I visited several of the most interesting institutions of that capital.
In the course of one of our drives, I alluded to the advantages of inheriting a distinguished name, as in the case of the second Herschel. His remark was, \\\"For my own part,I think it a great disadvantage. Such a man must feel in the position of one inheriting a vast estate, so deeply mortgaged that he can never hope, by any efforts of his own, to redeem it.\\\"
Without reverting to the philosophic, but unromantic, views of our origin taken by Darwin, I shall pass over the long history of our progress from a monad up to man, and commence tracing my ancestry as the world generally do: namely, as soon as there is the slightest ground for conjecture. Although I have contended for the Mosaic date of the creation of man as long as I decently could, and have even endeavoured to explain away [1] some of the facts relied upon to prove man's long anterior origin; yet I must admit that the continual accumulation of evidence probably will, at last, compel me to acknowledge that, in this single instance, the writings of Moses may have been misapprehended.
Let us, therefore, take for granted that man and certain extinct races of animals lived together, thousands of years before Adam. We find, at that period, a race who formed knives, and hammers, and arrow-heads out of flint. Now, considering my own inveterate habit of contriving tools, it is more probable that I should derive my passion by hereditary transmission from these original tool-makers, than from any other inferior race existing at that period.
Many years ago I met a very agreeable party at Mr. Rogers' table. Somebody introduced the subject of ancestry. I remarked that most people are reluctant to acknowledge as their father or grandfather, any person who had committed a dishonest action or a crime. But that no one ever scrupled to be proud of a remote ancestor, even though he might have been a thief or a murderer. Various remarks were made, and reasons assigned, for this tendency of the educated mind. I then turned to my next neighbour, Sir Robert H. Inglis, and asked him what he would do, supposing he possessed undoubted documents, that he was lineally descended from Cain.
Sir Robert said he was at that moment proposing to himself the very same question. After some consideration, he said he should burn them; and then inquired what I should do in the same circumstances. My reply was, that I should preserve them: but simply because I thought the preservation of any fact might ultimately be useful.
I possess no evidence that I am descended from Cain. If any herald suppose that there may be such a presumption, I think it must arise from his confounding Cain with Tubal Cain, who was a great worker in iron. Still, however he might argue that, the probabilities are in favour of his opinion: for I, too, work in iron. But a friend of mine, to whose kind criticisms I am much indebted, suggests that as Tubal Cain invented the Organ, this probability is opposed to the former one.
The next step in my pedigree is to determine whence the origin of my modern family name.
Some have supposed it to be derived from the cry of sheep. If so, that would point to a descent from the Shepherd Kings. Others have supposed it is derived from the name of a place called Bab or Babb, as we have, in the West of England, Bab Tor, Babbacombe, &c. But this is evidently erroneous; for, when a people took possession of a desert country, its various localities could possess no names; consequently, the colonists could not take names from the country to which they migrated, but would very naturally give their own names to the several lands they appropriated: \\\"mais revenons à nos moutons.\\\"
How my blood was transmitted to me through more modern races, is quite immaterial, seeing the admitted antiquity of the flint-workers.
In recent times, that is, since the Conquest, my knowledge of the history of my family is limited by the unfortunate omission of my name from the roll of William's followers. Those who are curious about the subject, and are idlers, may, if they think it worth while, search all the parish registers in the West of England and elsewhere.
The light I can throw upon it is not great, and rests on a few documents, and on family tradition. During the past four generations I have no surviving collateral relatives of my own name.
The name of Babbage is not uncommon in the West of England. One day during my boyhood, I observed it over a small grocer's shop, whilst riding through the town of Chudley. I dismounted, went into the shop, purchased some figs, and found a very old man of whom I made inquiry as to his family. He had not a good memory himself, but his wife told me that his name was Babb when she married him, and that it was only, during the last twenty years he had adopted the name of Babbage, which, the old man thought, sounded better. Of course I told his wife that I entirely agreed with her husband, and thought him a very sensible fellow.
The craft most frequently practised by my ancestors seems to have been that of a goldsmith, although several are believed to have practised less dignified trades.
In the time of Henry the Eighth one of my ancestors, together with a hundred men, were taken prisoners at the siege of Calais.
When William the Third landed in Torbay, another ancestor of mine, a yeoman possessing some small estate, undertook to distribute his proclamations. For this bit of high treason he was rewarded with a silver medal, which I well remember seeing, when I was a boy. It had descended to a very venerable and truthful old lady, an unmarried aunt, the historian of our family, on whose authority the identity of the medal I saw with that given by King William must rest.
Another ancestor married one of two daughters, the only children of a wealthy physician, Dr. Burthogge, an intimate friend and correspondent of John Locke.
Somewhere about 1700 a member of my family, one Richard Babbage, who appears to have been a very wild fellow, having tried his hand at various trades, and given them all up, offended a wealthy relative.
To punish this idleness, his relative entailed all his large estates upon eleven different people, after whom he gave it to this Richard Babbage, who, had there been no entail, would have taken them as heir-at-law.
Ten of these lives had dropped, and the eleventh was in a consumption, when Richard Babbage took it into his head to go off to America with Bamfylde Moore Carew, the King of the Beggars.
The last only of the eleven lives existed when he embarked, and that life expired within twelve months after Richard Babbage sailed. The estates remained in possession of the representatives of the eleventh in the entail. If it could have been proved that Richard Babbage had survived twelve months after his voyage to America, these estates would have remained in my own branch of the family.
I possess a letter from Richard Babbage, dated on board the ship in which he sailed for America.
In the year 1773 it became necessary to sell a portion of this property, for the purpose of building a church at Ashbrenton. A private Act of Parliament was passed for that purpose, in which the rights of the true heir were reserved.\");";
$insertPhotoCollectionTable = "INSERT INTO MyDB.photocollection (photoCollectionId,title,createdBy) VALUES
(1,\"Conferences\",\"charles@ucl.ac.uk\"),
(2,\"Difference Engine\", \"charles@ucl.ac.uk\")";
$insertPhotosTable = "INSERT INTO MyDB.photos (photoCollectionId,imageReference) VALUES
(1, \"/images/photoCollection/12.jpg\"),
(1, \"/images/photoCollection/13.jpg\"),
(1, \"/images/photoCollection/14.jpg\"),
(1, \"/images/photoCollection/15.jpg\"),
(1, \"/images/photoCollection/16.jpg\"),
(2, \"/images/photoCollection/17.jpg\"),
(2, \"/images/photoCollection/18.png\"),
(2, \"/images/photoCollection/19.jpg\"),
(2, \"/images/photoCollection/20.jpg\"),
(2, \"/images/photoCollection/21.jpg\"),
(2, \"/images/photoCollection/22.jpg\"),
(2, \"/images/photoCollection/23.jpg\"),
(2, \"/images/photoCollection/24.jpg\"),
(2, \"/images/photoCollection/25.jpg\")";


$insertCircleOfFriendsTable = "INSERT INTO MyDB.circleOfFriends (circleFriendsId, circleOfFriendsName) VALUES
(1,\"lmao\"),
(2,\"lol\"),
(3,\"The CS Friends\"),
(4,\"The Science Club\")";


$insertUserCircleRelationshipsTable = "INSERT INTO MyDB.userCircleRelationships (email, circleFriendsId) VALUES
(\"ada@ucl.ac.uk\",1),
(\"ada@ucl.ac.uk\",2),
(\"ada@ucl.ac.uk\",3),
(\"alan@ucl.ac.uk\",1),
(\"charles@ucl.ac.uk\",1),
(\"charles@ucl.ac.uk\",3),
(\"charles@ucl.ac.uk\",4),
(\"tim@ucl.ac.uk\",1),
(\"vicky@ucl.ac.uk\",1),
(\"vicky@ucl.ac.uk\",3),
(\"larry@ucl.ac.uk\",1),
(\"charles@ucl.ac.uk\",2)";


$insertCommentsTable = "INSERT INTO MyDB.comments (photoId,email,commentText) VALUES
(1,\"ada@ucl.ac.uk\", \"Which conference was this one?\"),
(1,\"ken@ucl.ac.uk\", \"This is a good photo!\"),
(1,\"larry@ucl.ac.uk\", \"I think the light in this picture isn't that good. Could have been better! And why didn't you invite me to this conference?\"),
(2,\"ken@ucl.ac.uk\", \"This reminded me...Have you seen where the annual conference is going to take place this year?\"),
(2,\"charles@ucl.ac.uk\",\"Yes, I have. Have you seen who the guest speakers are?\")";

$insertAnnotationsTable = "INSERT INTO MyDB.annotations
(`annotationsId`, `photoId`, `email`, `coordinateX`, `coordinateY`, `annotationText`)
VALUES
('1', '1', 'charles@ucl.ac.uk', '10', '11', 'Annotations!'),
('2', '2', 'charles@ucl.ac.uk', '20', '30', 'Annotations!'),
('3', '3', 'charles@ucl.ac.uk', '40', '40', 'Annotations!'),
('4', '4', 'charles@ucl.ac.uk', '20', '10', 'Annotations!'),
('5', '5', 'charles@ucl.ac.uk', '11', '1', 'Annotations!')";

$insertMessages = "INSERT INTO MyDB.messages (emailTo, emailFrom, messageText) VALUES
(\"ada@ucl.ac.uk\",\"charles@ucl.ac.uk\",\"Dear Ada, How are you? Haven't seen you in a while\"),
(\"charles@ucl.ac.uk\",\"ada@ucl.ac.uk\",\"Hello, Charles. We haven't talked for quite a while. How have you been?\"),
(\"ada@ucl.ac.uk\",\"charles@ucl.ac.uk\",\"I wanted to ask you if you have seen the last paper I published?\"),
(\"charles@ucl.ac.uk\",\"ada@ucl.ac.uk\",\"I don't think so. What is it about and where did you publish it?\"),
(\"3\",\"charles@ucl.ac.uk\",\"Hello World\"),
(\"4\",\"ada@ucl.ac.uk\",\"Invitation\")";

$populatingTables = [
    $insertRolesTable,
    $insertRightsTable,
    $insertUsersTable,
    $insertFriendshipTable,
    $insertBlogsTable,
    $insertPostsTable,
    $insertPhotoCollectionTable,
    $insertPhotosTable,
    $insertCircleOfFriendsTable,
    $insertUserCircleRelationshipsTable,
    $insertCommentsTable,
    $insertAnnotationsTable,
    $insertMessages

];

foreach ($populatingTables as $sqlquery) {
    echo nl2br("\n"); //Line break in HTML conversion
  echo "<b>Executing SQL statement: </b>";
    echo $sqlquery; //Dispay statement being executed
  echo nl2br("\n");
    $q= $pdo->prepare($sqlquery);
    if ($q->execute() === true) {
        echo "<b><font color='green'>SQL statement performed correctly</b></font>";
    } else {
        echo "<b><font color='red'>Error executing statement: </b></font>" . $pdo->error;
    }
}
  Database::disconnect();
