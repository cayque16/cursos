FROM mysql:5.7

ENV TZ=America/Sao_Paulo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > etc/timezone

RUN echo "America/Sao_Paulo" > /etc/timezone

EXPOSE 33006

CMD ["mysqld"]