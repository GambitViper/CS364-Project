CREATE TABLE In_Collection(
  card_id INT NOT NULL,
  collection_id INT NOT NULL,
  amount INT NOT NULL,
  CONSTRAINT fk_card_in_collection FOREIGN KEY (card_id) REFERENCES Card(card_id),
  CONSTRAINT fk_collection_in_collection FOREIGN KEY (collection_id) REFERENCES Collection(collection_id)
);

CREATE TABLE In_Deck(
  deck_id INT NOT NULL,
  card_id INT NOT NULL,
  CONSTRAINT fk_deck_in_deck FOREIGN KEY (deck_id) REFERENCES Deck(deck_id),
  CONSTRAINT fk_card_in_deck FOREIGN KEY (card_id) REFERENCES Card(card_id)
);

CREATE TABLE Has_Deck(
  deck_id INT NOT NULL,
  collection_id INT NOT NULL,
  CONSTRAINT fk_deck_has_deck FOREIGN KEY (deck_id) REFERENCES Deck(deck_id),
  CONSTRAINT fk_collection_has_deck FOREIGN KEY (collection_id) REFERENCES Collection(collection_id)
);

-- Add users
INSERT INTO User (user_id, first_name, last_name, collection_id, username, password) VALUES( 1,'zach','baklund',1,'user','user');

-- Add Collections for users
INSERT INTO Collection(collection_id, collection_size) VALUES(1, 0);

-- Add Decks into collections
INSERT INTO Deck(deck_id, deck_name, deck_wins, deck_losses, creation_date) VALUES(1, 'noob slayer', 3, 5, '05/03/2018');

-- Add Card to user collection
INSERT INTO In_Collection(card_id, collection_id, amount) VALUES(435290, 1, 2);

--Add one to card count
UPDATE In_Collection
SET amount = 1 + (SELECT amount
                  FROM In_Collection 
                  WHERE collection_id == 1 AND card_id == 435289)-- replace 2 with new values
WHERE card_id = 435289; -- replace with card_id

--Delete one card in collection
UPDATE In_Collection
SET amount = (SELECT amount
              FROM In_Collection
              WHERE collection_id == 1 AND card_id == 435289) - 1
WHERE card_id == 435289;

-- Updating collection size
UPDATE Collection
SET collection_size = (SELECT sum(amount)
                       FROM In_Collection
                       WHERE collection_id == 1)
WHERE Collection.collection_id == 1;

-- Deleting tuples if amount is 0 or less
DELETE FROM In_Collection
WHERE amount <= 0;

-- Add card to a collection's deck
INSERT INTO In_Deck(deck_id, card_id, card_amount) VALUES(1, 435152, 1);

-- Total collection price
SELECT sum(usd_price * amount), sum(usd_foil_price  * amount)
FROM User NATURAL JOIN In_Collection NATURAL JOIN Card
WHERE User.user_id == 1; --replace 1 with user searching

-- Cards in collection not in Decks
SELECT Card.*, In_Collection.amount
FROM User NATURAL JOIN In_Collection LEFT OUTER JOIN In_Deck ON In_Deck.card_id == In_Collection.card_id NATURAL JOIN Card
WHERE deck_id IS NULL AND User.user_id == 1; -- replace 1 with user searching

-- Percent collected unique                                
SELECT user_count.number * 1.0 / card_count.number * 100 AS percent_collected
FROM (SELECT count(*) AS number
      FROM User NATURAL JOIN In_Collection NATURAL JOIN Card
      WHERE User.user_id == 1 ) AS user_count JOIN (SELECT count(*) AS number
                                           FROM Card) AS card_count