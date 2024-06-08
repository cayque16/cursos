export const categoryResponse = {
    "data": [
        {
          "id": "e14a89ed-185e-11ef-baec-0242ac120003",
          "name": "Filme Experimental",
          "description": "Filmes que desafiam as convenções tradicionais do cinema, utilizando técnicas inovadoras de narrativa, edição e visuais para explorar novas formas de expressão.",
          "is_active": true,
          "created_at": "2024-05-22 14:16:39"
        },
        {
          "id": "e0c9af5c-185e-11ef-baec-0242ac120003",
          "name": "Policial",
          "description": "Filmes que seguem a vida de policiais e detetives, explorando investigações criminais, perseguições e a aplicação da lei.",
          "is_active": true,
          "created_at": "2024-05-22 14:16:39"
        },
      ],
      "meta": {
        "total": 31,
        "current_page": 1,
        "last_page": 3,
        "first_page": 1,
        "per_page": 15,
        "to": 1,
        "from": 15
      }
};

export const categoryResponse2 = {
  "data": [
    {
      "id": "e0d237f6-185e-11ef-baec-0242ac120003",
      "name": "Romance",
      "description": "Filmes que se concentram em histórias de amor e relacionamentos, destacando os desafios e triunfos emocionais dos protagonistas.",
      "is_active": true,
      "created_at": "2024-05-22 14:16:39"
    },
    ],
    "meta": {
      "total": 31,
      "current_page": 2,
      "last_page": 3,
      "first_page": 1,
      "per_page": 15,
      "to": 1,
      "from": 15
    }
};

export const castMemberResponse = {
  data: [
    {
      id: "f55fca48-d422-48bf-b212-956215eddcaf",
      name: "Teste",
      type: 1,
      deleted_at: null,
      created_at: "2022-10-03T16:23:27+0000",
      updated_at: "2022-10-03T16:23:27+0000",
    },
  ],
  links: {
    first: "http://192.168.2.10:8080/api/cast_members?page=1",
    last: "http://192.168.2.10:8080/api/cast_members?page=7",
    prev: null,
    next: "http://192.168.2.10:8080/api/cast_members?page=2",
  },
  meta: {
    current_page: 1,
    from: 1,
    last_page: 7,
    path: "http://192.168.2.10:8080/api/cast_members",
    per_page: 15,
    to: 15,
    total: 100,
  },
};

export const castMemberResponsePage2 = {
  data: [
    {
      id: "f55fca48-d422-48bf-b212-956215eddcae",
      name: "Teste 2",
      type: 1,
      deleted_at: null,
      created_at: "2022-10-03T16:23:27+0000",
      updated_at: "2022-10-03T16:23:27+0000",
    },
  ],
  links: {
    first: "http://192.168.2.10:8080/api/cast_members?page=1",
    last: "http://192.168.2.10:8080/api/cast_members?page=7",
    prev: "http://192.168.2.10:8080/api/cast_members?page=1",
    next: "http://192.168.2.10:8080/api/cast_members?page=3",
  },
  meta: {
    current_page: 2,
    from: 1,
    last_page: 7,
    path: "http://192.168.2.10:8080/api/cast_members",
    per_page: 15,
    to: 15,
    total: 100,
  },
};
