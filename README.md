# beams-calculator-lib

@todo: Translate this:

# Balken3

## Vooraf
- Elk materiaal heeft een breedte, dikte, lengte, en label
- Idem voor elk deel
- Geen aantallen: wanneer een materiaal 10 keer in opslag ligt, dan komt deze 10 keer (dus elk balkje afzonderlijk) in de lijst (idem voor delen)

## Stappenplan (hoe de calculator zou moeten werken):
1. Materialen groeperen op breedte en dikte
 -- elke lengte is een apart materiaal binnen de groep
2. Delen toevoegen aan passende groep
 -- alarm slaan wanneer geen groep gevonden kon worden voor een deel
3. Per groep de scenario's bepalen
 -- elke combinatie van delen/materialen is een scenario (mogelijk of onmogelijk, maakt hier nog niet uit)
4. Alle scenario's van alle groepen berekenen
5. Per groep wint het scenario met het minste verlies

## Bepalen van de scenario's
Wanneer het aantal materialen en delen bekend is, kan het aantal "partities" berekend worden.
Voorbeeld, met 3 delen en 3 materialen:

### 1: Partities
Verdeel 3 delen
[3], [2,1], [1,1,1]

### 2: Aangevuld
3 delen op 3 materialen: zorg dat elk groepje 3 waardes heeft.
[3,0,0], [2,1,0], [1,1,1]

### 3: Verdeeld
[3,0,0], [0,3,0], [0,0,3], [2,1,0], [0,2,1], [1,0,2], [1,1,1]
Uitleg van de groepjes:
1. 3 delen uit het eerste materiaal, geen uit de andere materialen
2. 3 delen uit het tweede materiaal, geen uit de andere materialen
3. 3 delen uit het derde materiaal, geen uit de andere materialen
4. 2 delen uit het eerste materiaal, 1 deel uit het tweede, geen uit het derde
5. geen delen uit het eerste, 2 delen uit het tweede materiaal, 1 deel uit het derde
5. 1 deel uit het eerste materiaal, geen deel uit het tweede, 2 delen uit het derde
6. 1 deel uit het eerste materiaal, 1 deel uit het tweede, 1 deel uit het derde

### 4: Delen toewijzen
Omzetten van dit lijstje:
[3,0,0], [0,3,0], [0,0,3], [2,1,0], [0,2,1], [1,0,2], [1,1,1]
naar:
		Mat.1		Mat.2		Mat.3
00 [	[1,2,3], 	[],			[]		],
01 [	[3,1,2], 	[],			[]		],
02 [	[2,3,1], 	[],			[]		],
03 [	[],			[1,2,3],	[]		],
04 [	[],			[3,1,2],	[]		],
05 [	[],			[2,3,1],	[]		],
06 [	[],			[],			[1,2,3] ],
07 [	[],			[],			[3,1,2] ],
08 [	[],			[],			[2,3,1] ],
09 [	[1,2],		[3],		[]		],
10 [	[2,3],		[1],		[]		],
11 [	[3,1],		[2],		[]		],
12 [	[],			[1,2],		[3]		],
13 [	[],			[2,3],		[1]		],
14 [	[],			[3,1],		[2]		],
15 [	[1],		[],			[2,3]	],
16 [	[2],		[],			[3,1]	],
17 [	[3],		[],			[1,2]	],
18 [	[1],		[2],		[3]		],
19 [	[2],		[3],		[1]		],
20 [	[3],		[1],		[2]		]

Merk op dat elk nummer nu niet een aantal is, maar een nummer van een Deel.

