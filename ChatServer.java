import java.io.*;
import java.net.*;
import java.text.SimpleDateFormat;
import java.util.*;

public class ChatServer {
private static final int PORT = 8888;
private ServerSocket serverSocket;
private Map<String, List<PrintWriter>> rooms = new HashMap<>(); // 각 방에 대한 클라이언트 목록


public ChatServer() {
    try {
        serverSocket = new ServerSocket(PORT);
        System.out.println("서버가 시작되었습니다. 포트 " + PORT + "에서 대기 중...");
    } catch (IOException e) {
        e.printStackTrace();
    }
}

public void start() {
    while (true) {
        try {
            Socket clientSocket = serverSocket.accept();
            new ClientManagerThread(clientSocket).start();
        } catch (IOException e) {
            e.printStackTrace();
        }
    }
}


private class ClientManagerThread extends Thread {
    private Socket socket;
    private PrintWriter out;
    private String roomName;
    private String enterRoom;

    public ClientManagerThread(Socket socket) {
        this.socket = socket;
    }

    public void run() {
        try {
            BufferedReader in = new BufferedReader(new InputStreamReader(socket.getInputStream()));
    
            this.roomName = in.readLine(); // 방 정보
    
            out = new PrintWriter(socket.getOutputStream(), true);
    
            synchronized (rooms) {
                List<PrintWriter> clients = rooms.get(roomName);
                if (clients == null) {
                    clients = new ArrayList<>();
                    rooms.put(roomName, clients);
                }
                // 존재 클라이언트인지 확인 확인하고 추가
                if (!clients.contains(out)) {
                    clients.add(out);
                }
            }
    
            boolean isTwoOrMoreClients = rooms.get(roomName).size() >= 2;
            notifyClientStatusChange(roomName, isTwoOrMoreClients);
    
            String message;
            while ((message = in.readLine()) != null) {
                // 메시지 파싱
                String[] parts = message.split(">");
                if (parts.length >= 4) {
                    String roomName = parts[0]; // 방 정보
                    String senderID = parts[1];
                    String messageContent = parts[2]; // 메시지 내용
                    String messageID = parts[3];
    
                    if (roomName != null && roomName.equals(this.roomName)) {
                        if (messageContent.equals("alsldlfkdscjndnjdidjaknckajsnkqwduin-=e3wqsa")) {
                            System.out.println("messageContent 나 나갔다 ." + messageContent);
                            break; // 쓰레드 종료
                        } else {
                            // 메시지를 모든 클라이언트에게 방송
                            broadcastMessage(roomName, message);
                        }
                    }
                }
            }
        } catch (IOException e) {
            e.printStackTrace();
        } finally {
            // 사용자가 방을 나가는 것을 처리
            synchronized (rooms) {
                List<PrintWriter> clients = rooms.get(roomName);
                if (clients != null) {
                    clients.remove(out);
                    // 방에 아무도 없다면 방 삭제
                    if (clients.isEmpty()) {
                        rooms.remove(roomName);
                    }
                }
            }
            try {
                socket.close();
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
    }

}

private void broadcastMessage(String roomName, String message) {
    List<PrintWriter> clients = rooms.get(roomName);
    System.out.println("메시지의 값" + message);
    if (clients != null) {
        for (PrintWriter client : clients) {
            if (clients.size() > 1) {
                // 클라이언트가 두 명일 때는 특별한 메시지와 함께 메시지를 전달
                System.out.println("두명일때다.");
                String currentTime = getCurrentTime();
                String currentDate = getCurrentDate();
                client.println(message + ">" + "ENTER MESSAGE" + ">" + currentTime + ">" + currentDate);
             
            } else {
                // 클라이언트가 한 명일 때는 일반적으로 메시지 전달
                System.out.println("한명일떄 들어옴?");
                String currentTime = getCurrentTime();
                String currentDate = getCurrentDate();
                client.println(message + ">" + "ONE MESSAGE" + ">" + currentTime + ">" + currentDate);
            }
            client.flush();
        }
        // 클라이언트의 상태 변경 알림 전송
    }
}

private String getCurrentTime() {
    SimpleDateFormat sdf = new SimpleDateFormat("HH:mm");
    sdf.setTimeZone(TimeZone.getTimeZone("Asia/Seoul")); // 한국 시간대로 설정
    return sdf.format(new Date());
}

private String getCurrentDate() {
    SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd");
    sdf.setTimeZone(TimeZone.getTimeZone("Asia/Seoul")); // 한국 시간대로 설정
    return sdf.format(new Date());
}



private void notifyClientStatusChange(String roomName, boolean isTwoOrMoreClients) {
    List<PrintWriter> clients = rooms.get(roomName);
    if (clients != null) {
        for (PrintWriter client : clients) {
            if (isTwoOrMoreClients) {
                client.println("방에 두 명 이상이 있습니다.");
            } else {
                client.println("방에 한 명 또는 그 이하입니다.");
            }
            client.flush();
        }
    }
}




public static void main(String[] args) {
    ChatServer server = new ChatServer();
    server.start();
}
}
